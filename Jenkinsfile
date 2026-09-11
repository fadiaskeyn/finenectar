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

        stage('Prepare Environment & Credentials') {
            steps {
                echo 'Preparing .env file from Jenkins Secret Credentials...'
                withCredentials([file(credentialsId: 'finenectar-env', variable: 'SECRET_ENV')]) {
                    sh '''
                        cp "$SECRET_ENV" .env
                        echo "Successfully copied secret .env file."
                    '''
                }
                script {
                    echo 'Checking Docker Compose tool availability...'
                    sh '''
                        mkdir -p bin
                        if docker compose version >/dev/null 2>&1; then
                            echo "Docker Compose CLI plugin is available."
                        elif [ -f bin/docker-compose ]; then
                            echo "Using local bin/docker-compose."
                        else
                            echo "Docker Compose plugin missing in container. Downloading standalone docker-compose..."
                            curl -sSL "https://github.com/docker/compose/releases/download/v2.29.1/docker-compose-linux-x86_64" -o bin/docker-compose
                            chmod +x bin/docker-compose
                        fi
                    '''
                }
            }
        }

        stage('Build Containers') {
            steps {
                echo 'Building Docker images...'
                sh '''
                    export PATH="$(pwd)/bin:$PATH"
                    if [ -f bin/docker-compose ]; then
                        bin/docker-compose build
                    else
                        docker compose build
                    fi
                '''
            }
        }

        stage('Deploy & Start Services') {
            steps {
                echo 'Starting Docker Compose stack...'
                sh '''
                    export PATH="$(pwd)/bin:$PATH"
                    if [ -f bin/docker-compose ]; then
                        bin/docker-compose up -d
                    else
                        docker compose up -d
                    fi
                '''
            }
        }

        stage('Database Migration & Cache Optimization') {
            steps {
                echo 'Running Laravel database migrations and optimization commands...'
                sh '''
                    export PATH="$(pwd)/bin:$PATH"
                    DC="docker compose"
                    if [ -f bin/docker-compose ]; then
                        DC="bin/docker-compose"
                    fi

                    echo "Waiting for database container..."
                    sleep 10

                    $DC exec -T app php artisan key:generate --force || true
                    $DC exec -T app php artisan migrate --force
                    $DC exec -T app php artisan config:cache
                    $DC exec -T app php artisan route:cache
                    $DC exec -T app php artisan view:cache
                    $DC exec -T app php artisan storage:link || true
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
            sh '''
                export PATH="$(pwd)/bin:$PATH"
                DC="docker compose"
                if [ -f bin/docker-compose ]; then
                    DC="bin/docker-compose"
                fi
                $DC logs --tail=50 || $DC logs
            '''
        }
    }
}
