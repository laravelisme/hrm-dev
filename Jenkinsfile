pipeline {
    agent any

    stages {

        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Build') {
            steps {
                sh 'docker compose build'
            }
        }

        stage('Deploy') {
            steps {
                sh 'docker compose up -d'
            }
        }

        stage('Laravel Setup') {
            steps {
                sh '''
                docker compose exec -T php_laravel_1 php artisan key:generate --force || true
                docker compose exec -T php_laravel_1 php artisan migrate --force
                docker compose exec -T php_laravel_1 php artisan optimize
                '''
            }
        }
    }

    post {
        success {
            echo "✅ Deploy sukses"
        }
        failure {
            echo "❌ Deploy gagal"
        }
    }
}
