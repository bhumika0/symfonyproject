pipeline {
    agent any

    environment {
        // Define environment variables as needed
        SSH_KEY = credentials('bhumika')
        SSH_USER = 'bhumika'
        SSH_HOST = 'localhost'
        DEPLOY_PATH = '/var/www/html/cauldron-overflow-prod'
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
                sh 'npm install'
                sh 'yarn install'
                sh 'NODE_OPTIONS=--openssl-legacy-provider yarn encore production'
            }
        }

        stage('Build and Deploy') {
            steps {
                script {
                    sshagent(['bhumika']) {
                        // Define the source directory as the Jenkins workspace
                        def sourceDir = "${WORKSPACE}"

                        // Use rsync to synchronize files, including deletions, and exclude specific files
                        sh """
                            rsync -r --delete  \
                            --exclude='.git/' \
                            --exclude='node_modules/' \
                            --exclude='.env' \
                            --exclude='.env.test' \
                            ${sourceDir}/ ${SSH_USER}@${SSH_HOST}:${DEPLOY_PATH}
                        """
                    }
                }
            }
        }

        stage('Database Migrations') {
            steps {
                script {
                    sshagent(['bhumika']) {
                        sh "ssh -i ${SSH_KEY} ${SSH_USER}@${SSH_HOST} 'cd ${DEPLOY_PATH} && php bin/console doctrine:migrations:migrate --no-interaction'"
                    }
                }
            }
        }
    }
}
