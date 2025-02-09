variable "app_version" {
  description = "The version of the application to deploy"
  type        = string
}

variable "shared_state_encryption_key" {
  description = "The encryption key to use for the shared state"
  type        = string
}
