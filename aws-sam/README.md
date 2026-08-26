# AWS SAM WordPress Template

Deploys WordPress on an EC2 instance backed by a managed RDS MySQL database.

## What it creates

- VPC with one public subnet (web server) and two private subnets (database, required by RDS for a subnet group)
- EC2 instance running Amazon Linux 2023, bootstrapped via UserData to install Apache, PHP, and WordPress
- RDS MySQL 8.0 instance in the private subnets, encrypted at rest, reachable only from the web server's security group
- Security groups restricting DB access to the web tier and SSH access to a CIDR you specify

## Deploy

```bash
sam build --template-file aws-sam/template.yaml
sam deploy --guided \
  --template-file aws-sam/template.yaml \
  --stack-name my-wordpress-stack \
  --capabilities CAPABILITY_IAM
```

You'll be prompted for parameters, notably:

- `KeyName` — an existing EC2 key pair for SSH access
- `SSHLocation` — CIDR allowed to SSH in (default `0.0.0.0/0`; restrict this to your IP)
- `DBPassword` — RDS master password (8+ characters)

## After deploy

Check the stack outputs for `WebsiteURL` to reach the WordPress install wizard, and `DatabaseEndpoint` for the RDS connection endpoint.

## Notes

- The DB uses `DeletionPolicy: Snapshot`, so deleting the stack snapshots the database rather than destroying it outright.
- For production use, consider adding an Application Load Balancer, an Auto Scaling group instead of a single instance, HTTPS via ACM, and storing `DBPassword` in Secrets Manager instead of passing it as a plain parameter.
