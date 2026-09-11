pipeline {
    agent any

    environment {
        APP_NAME = 'finenectar'
        COMPOSE_PROJECT_NAME = 'finenectar'
    }

    stages {
        stage('Checkout') {
            steps {
                echo 'Checking out source code...'
                checkout scm
            }
        }

        stage('Prepare Environment') {
            steps {
                script {
                    echo 'Preparing environment files...'
                    sh '''
                        if [ ! -f .env ]; then
                            echo ".env file not found. Copying .env.docker..."
                            cp .env.docker .env
                            # Generate key if needed inside container later
                        fi
                    '''
                }
            }
        }

        stage('Build Containers') {
            steps {
                echo 'Building Docker images...'
                sh 'docker compose build --no-cache'
            }
        }

        stage('Deploy & Start Services') {
            steps {
                echo 'Starting Docker Compose stack...'
                sh 'docker compose up -d'
            }
        }

        stage('Database Migration & Cache Optimization') {
            steps {
                echo 'Running Laravel database migrations and optimization commands...'
                sh '''
                    # Wait for database container to be ready
                    echo "Waiting for MySQL database..."
                    sleep 10

                    # Run migrations and cache commands inside app container
                    docker compose exec -T app php artisan key:generate --force || true
                    docker compose exec -T app php artisan migrate --force
                    docker compose exec -T app php artisan config:cache
                    docker compose exec -T app php artisan route:cache
                    docker compose exec -T app php artisan view:cache
                    docker compose exec -T app php artisan storage:link || true
                '''
            }
        }

        stage('Health Check') {
            steps {
                echo 'Verifying application deployment...'
                sh '''
                    sleep 5
                    curl --fail http://localhost:8000 || curl --fail http://172.17.0.1:8000 || exit 1
                    echo "Health check passed!"
                '''
            }
        }
    }

    post {
        always {
            echo 'Pipeline execution completed.'
        }
        success {
            echo 'Deployment successful!'
        }
        failure {
            echo 'Deployment failed! Checking logs...'
            sh 'docker compose logs -n 50'
        }
    }
}
