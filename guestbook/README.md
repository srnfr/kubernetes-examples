# Example: Guestbook application on Kubernetes

This directory contains the source code and Kubernetes manifests for PHP
Guestbook application.

Follow the tutorial at https://kubernetes.io/docs/tutorials/stateless-application/guestbook/.

In this branch deploy the Resid Sentinel Cluster with helm :

helm repo add bitnami https://charts.bitnami.com/bitnami

helm install redis -f https://raw.githubusercontent.com/srnfr/kubernetes-examples/frontend-with-env/guestbook/redis-values.yaml bitnami/redis
