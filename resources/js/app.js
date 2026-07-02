import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
	const revealElements = document.querySelectorAll('[data-reveal]');

	if (!revealElements.length) {
		return;
	}

	document.documentElement.classList.add('reveal-init');

	const observer = new IntersectionObserver((entries, currentObserver) => {
		entries.forEach((entry) => {
			if (!entry.isIntersecting) {
				return;
			}

			entry.target.classList.add('is-visible');
			currentObserver.unobserve(entry.target);
		});
	}, {
		threshold: 0.12,
		rootMargin: '0px 0px -40px 0px',
	});

	revealElements.forEach((element) => observer.observe(element));
});
