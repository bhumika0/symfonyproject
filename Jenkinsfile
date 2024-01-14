pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Install Dependencies') {
            steps {
                sh '/usr/local/bin/composer install --no-dev --optimize-autoloader'
            }
        }
    }
}
