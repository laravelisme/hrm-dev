pipeline {
    agent any

    environment {
        COMPOSE_PROJECT_NAME = "hrm-dev"
        APP_PATH = "/var/www/hrm-dev"
    }

    stages {

        stage('Clone or Pull Repo') {
            steps {
                dir(APP_PATH) {
                    sh """
                    # Jika repo belum ada, clone
                    if [ ! -d ".git" ]; then
                        git clone git@github.com:laravelisme/hrm-dev.git .
                    else
                        git fetch origin main
                        git reset --hard origin/main
                    fi
                    """
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

        stage('Setup Laravel in Container') {
            steps {
                dir(APP_PATH) {
                    sh """
                    docker compose exec php_laravel_1 bash -c '
                        cp .env.example .env || true
                        composer install --no-interaction --prefer-dist
                        php artisan key:generate
                        php artisan migrate:rollback --force || true
                        php artisan migrate --force
                    '
                    """
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
