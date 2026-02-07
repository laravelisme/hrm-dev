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
                    // Sesuaikan path .env jika beda
                    sh "cp src/.env.example .env"
                }
            }
        }

        stage('Rebuild & Start Containers') {
            steps {
                dir(APP_PATH) {
                    // Down dulu, jangan hapus volume biar DB aman
                    sh "docker compose down"
                    sh "docker compose up -d --build"
                }
            }
        }

        stage('Run Migration') {
            steps {
                dir(APP_PATH) {
                    sh "docker compose exec php_laravel_1 php artisan migrate --force"
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
