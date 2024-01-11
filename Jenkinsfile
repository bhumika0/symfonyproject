pipeline {
    agent any

    environment {
        // Define environment variables as needed
        SSH_KEY = credentials('your-ssh-key-credential-id')
        SSH_USER = 'your-ssh-username'
        SSH_HOST = 'your-ssh-host'
        DEPLOY_PATH = '/path/to/deploy'
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Install Dependencies') {
            steps {
                sh 'composer install --no-dev --optimize-autoloader'
            }
        }

        stage('Run Tests') {
            steps {
                sh 'php bin/phpunit'
            }
        }

        stage('Build and Deploy') {
            steps {
                script {
                    // Clear cache and warmup for production
                    sh 'php bin/console cache:clear --env=prod --no-warmup'
                    sh 'php bin/console cache:warmup --env=prod'

                    // Deploy to server via SSH
                    sshagent(['your-ssh-key-credential-id']) {
                        sh "scp -r -i ${SSH_KEY} . ${SSH_USER}@${SSH_HOST}:${DEPLOY_PATH}"
                    }
                }
            }
        }
    }
}
