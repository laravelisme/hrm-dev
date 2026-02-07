pipeline {
    agent any

    environment {
        COMPOSE_PROJECT_NAME = "html"
        APP_PATH = "/var/www/html"
    }

    stages {

        stage('Checkout Code') {
            steps {
                dir(APP_PATH) {
                    git branch: 'main',
                        url: 'git@github.com:laravelisme/hrm-dev.git',
                        credentialsId: 'github-ssh'
                }
            }
        }

        stage('Copy .env') {
            steps {
                dir(APP_PATH) {
                    sh "cp src/.env.example src/.env || true"
                }
            }
        }

        stage('Install Composer Dependencies') {
            steps {
                dir(APP_PATH) {
                    sh "docker run --rm -v \$(pwd):/app -w /app composer install --no-interaction --prefer-dist --optimize-autoloader"
                }
            }
        }

        stage('Generate App Key') {
            steps {
                dir(APP_PATH) {
                    sh "docker run --rm -v \$(pwd):/app -w /app php:8.2-cli php artisan key:generate"
                }
            }
        }

        stage('Run Migration') {
            steps {
                dir(APP_PATH) {
                    sh "docker run --rm -v \$(pwd):/app -w /app php:8.2-cli php artisan migrate --force"
                }
            }
        }

        stage('Rebuild & Start Containers') {
            steps {
                dir(APP_PATH) {
                    sh "docker compose down"
                    sh "docker compose up -d --build"
                }
            }
        }
    }

    post {
        success {
            echo "🚀 DEPLOY SUKSES - Laravel Prod Updated"
        }
        failure {
            echo "❌ DEPLOY GAGAL - CEK LOG JENKINS"
        }
    }
}
