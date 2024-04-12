<template>
  <div class="container-lg mt-lg-3 mt-md-5 mt-sm-5">
    <div class="row">
      <img src="./../assets/medella-logo.svg" class="logo mx-auto" alt="logo" />
    </div>
    <div class="row mt-lg-4 mt-md-4 mt-sm-2" id="title">
      <div class="text-center">Medella Hospital Management System</div>
    </div>
    <div class="row mt-sm-2 mt-md-5 mt-lg-5">
      <div class="col-lg-4 col-md-5 col-sm-12 text-center banner">
        <img src="./../assets/heart-rate.png" alt="" class="banner-img" />
      </div>
      <div class="col-lg-8 col-md-7 col-sm-12 login-form">
        <div
          v-if="showError"
          class="alert alert-error alert-dismissible fade show error"
          role="alert"
        >
          {{ error_msg }}
        </div>
        <div class="card">
          <div class="card-header" alt="login">Login</div>
          <div class="card-body">
            <form method="POST" @submit.prevent="login">
              <div class="mb-3">
                <label for="email" class="form-label" alt="enter email">Enter Email*</label>
                <input
                  type="email"
                  id="email"
                  class="form-control"
                  aria-describedby="emailHelp"
                  alt="email input"
                  v-model="email"
                  required
                />
                <div class="form-text" alt="email help">
                  We'll never share your email with anyone else.
                </div>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label" alt="enter password"
                  >Enter Password*</label
                >
                <input
                  type="password"
                  class="form-control"
                  alt="password input"
                  id="password"
                  v-model="password"
                  required
                />
              </div>
              <div class="text-center">
                <button type="submit" class="btn px-5 py-2 btnLogin">Login</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import router from '@/router'
import { ref } from 'vue'

const email = ref('')
const password = ref('')
// const showAlert = ref(false)
const showError = ref(false)
const error_msg = ref([])

const login = async () => {
  const formData = new FormData()
  formData.append('email', email.value)
  formData.append('password', password.value)

  try {
    const response = await fetch('http://127.0.0.1:8000/api/login', {
      method: 'POST',
      body: formData
    })

    const data = await response.json()

    if (response.ok) {
      localStorage.setItem('accessToken', data.data.token)
      router.push('/dashboard')
    } else {
      showError.value = true
      error_msg.value = data.message
    }
  } catch (error) {
    console.log(error.message)
  }
}
</script>

<style scoped>
.banner-img {
  width: 300px;
  height: 300px;
}
/* Phone screen size */
@media (max-width: 576px) {
  .banner-img {
    width: 150px;
    height: 150px;
  }
  .banner {
    margin-left: auto;
    margin-right: auto;
  }
}

/* Tablet screen size */
@media (max-width: 768px) {
  .banner-img {
    width: 150px;
    height: 150px;
  }
}
.logo {
  width: 100px;
  height: 100px;
}
#title {
  font-size: 24px;
}
.btnLogin {
  background-color: #4da8f5;
  color: #fff;
}

.btnLogin:hover {
  background-color: #74b9f3;
  cursor: pointer;
}
.btnLogin:active {
  border: 2px solid #74b9f3;
  color: #fff;
}

.alert-error {
  background-color: #f3909078;
  color: #343a40;
}
</style>
