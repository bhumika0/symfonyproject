pipeline {
    agent any

    environment {
        // Define environment variables as needed
        SSH_KEY = credentials('bhumika')
        SSH_USER = 'bhumika'
        SSH_HOST = 'localhost'
        DEPLOY_PATH = '/var/www/html/cauldron-overflow-dev'
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Install Dependencies') {
            steps {
                sh '/usr/local/bin/composer install --optimize-autoloader'
            }
        }

        stage('Run Tests') {
            steps {
                sh 'php bin/phpunit'
            }
        }

        stage('Build and Deploy') {
            steps{
                script{
                    sshagent(['bhumika']) {
                        sh 'scp -r -i ${SSH_KEY} * ${SSH_USER}@${SSH_HOST}:${DEPLOY_PATH}'
                    } 
                }  
            }
        }
    }
}
