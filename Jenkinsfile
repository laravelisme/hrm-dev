pipeline {
    agent any

    environment {
        COMPOSE_PROJECT_NAME = "laravel_prod"
        APP_PATH = "/var/www/laravel-prod"
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

        stage('Build PHP Image') {
            steps {
                dir(APP_PATH) {
                    sh """
                    docker compose build php_laravel_1 php_laravel_2 laravel_queue
                    """
                }
            }
        }

        stage('Run Migration') {
            steps {
                dir(APP_PATH) {
                    sh """
                    docker compose run --rm php_laravel_1 php artisan migrate --force
                    """
                }
            }
        }

        stage('Restart Laravel Containers') {
            steps {
                dir(APP_PATH) {
                    sh """
                    docker compose up -d --no-deps php_laravel_1 php_laravel_2 laravel_queue
                    """
                }
            }
        }

        stage('Reload Nginx') {
            steps {
                dir(APP_PATH) {
                    sh """
                    docker compose exec nginx_laravel_1 nginx -s reload || true
                    docker compose exec nginx_laravel_2 nginx -s reload || true
                    docker compose exec nginx_loadbalancer nginx -s reload || true
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
