pipeline {
    agent any

    environment {
        // Define environment variables as needed
        SSH_KEY = credentials('/home/bhumika/.ssh/id_rsa.pub')
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

        // stage('Run Tests') {
        //     steps {
        //         sh 'php bin/phpunit'
        //     }
        // }

        stage('Build and Deploy') {
            script {
                // Deploy to server via SSH
                sshagent(['your-ssh-key-credential-id']) {
                    sh "scp -r -i ${SSH_KEY} . ${SSH_USER}@${SSH_HOST}:${DEPLOY_PATH}"
                }
            }
        }
        
    }
}
