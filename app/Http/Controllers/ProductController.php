<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    public function landing(): View
    {
        $checkoutProducts = Product::latest()->get();

        $featuredProducts = Product::active()
            ->where('is_featured', true)
            ->latest()
            ->take(6)
            ->get();

        $checkoutProduct = $featuredProducts->first()
            ?: $checkoutProducts->first();

        return view('welcome', compact('featuredProducts', 'checkoutProduct', 'checkoutProducts'));
    }

    public function dev(): View
    {
        $checkoutProducts = Product::latest()->get();

        $products = Product::active()
            ->latest()
            ->take(8)
            ->get();

        $checkoutProduct = $products->first()
            ?: $checkoutProducts->first();

        return view('dev', compact('products', 'checkoutProduct', 'checkoutProducts'));
    }

    public function index(Request $request): View
    {
        $products = Product::active()
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = '%' . $request->string('q')->toString() . '%';

                $query->where(function ($query) use ($keyword) {
                    $query->where('name', 'like', $keyword)
                        ->orWhere('description', 'like', $keyword)
                        ->orWhere('category', 'like', $keyword);
                });
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('category', $request->string('category')->toString());
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Product::active()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $relatedProducts = Product::active()
            ->whereKeyNot($product->id)
            ->latest()
            ->take(3)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function adminIndex(): View
    {
        $products = Product::latest()->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'product' => new Product([
                'price' => 35000,
                'stock' => 0,
                'is_active' => true,
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedProduct($request);
        $data['image_url'] = $this->storeProductImage($request);

        Product::create($data);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dibuat.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validatedProduct($request, $product);

        if ($request->hasFile('image')) {
            $this->deleteProductImageIfLocal($product);
            $data['image_url'] = $this->storeProductImage($request);
        }

        $product->update($data);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->deleteProductImageIfLocal($product);
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    private function validatedProduct(Request $request, ?Product $product = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'slug' => [
                'nullable',
                'string',
                'max:180',
                Rule::unique('products', 'slug')->ignore($product),
            ],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['required', 'integer', 'min:0'],
            'compare_at_price' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'net_weight' => ['nullable', 'string', 'max:80'],
            'category' => ['nullable', 'string', 'max:120'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'is_promo' => ['nullable', 'boolean'],
            'is_free_shipping' => ['nullable', 'boolean'],
        ]);

        $slug = filled($validated['slug'] ?? null)
            ? Str::slug($validated['slug'])
            : Str::slug($validated['name']);
        $validated['slug'] = $this->uniqueSlug($slug, $product);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_promo'] = $request->boolean('is_promo');
        $validated['is_free_shipping'] = $request->boolean('is_free_shipping');
        unset($validated['image']);

        return $validated;
    }

    private function storeProductImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        $path = $request->file('image')->store('products', 'public');

        return '/storage/' . ltrim($path, '/');
    }

    private function deleteProductImageIfLocal(Product $product): void
    {
        $imageUrl = (string) $product->getRawOriginal('image_url');
        $imageUrl = $product->normalizeImageUrl($imageUrl);

        if (! str_starts_with($imageUrl, '/storage/')) {
            return;
        }

        $path = Str::after($imageUrl, '/storage/');

        if ($path !== '') {
            Storage::disk('public')->delete($path);
        }
    }

    private function uniqueSlug(string $slug, ?Product $product = null): string
    {
        $baseSlug = $slug ?: Str::random(8);
        $candidate = $baseSlug;
        $counter = 2;

        while (Product::query()
            ->where('slug', $candidate)
            ->when($product, fn ($query) => $query->whereKeyNot($product->id))
            ->exists()) {
            $candidate = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $candidate;
    }
}
