<template>
  <div class="container">
    <!-- Modal -->
    <div
      class="modal fade"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      aria-labelledby="deactivateModal"
      aria-hidden="true"
      id="exampleModal"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Deactive Account</h1>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <img src="./../assets/trash.png" class="trash" alt="tarsh sticker" />
            <p class="mt-2">
              Deactivating account will remove this user information from the system. This cannot be
              undone. <br />
              Are you sure want to proceed?
            </p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn" data-bs-dismiss="modal">No, Cancel</button>
            <button
              type="button"
              class="btn btn-outline-secondary"
              id="btnDelete"
              data-bs-dismiss="modal"
              @click="btnDelete"
            >
              Yes, Deactivate
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-1 col-md-1 col-sm-2 ms-md-2 ms-sm-0">
        <button @click="back" class="btn btnBack">
          <i class="fi fi-rs-arrow-left backarrow backarrow"></i>
        </button>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-5 offset-lg-3 offset-md-2 offset-sm-0 mt-md-2 col-8">
        <span id="title" class="fw-medium mx-auto h3">User Information</span>
      </div>
      <div class="col-lg-3 col-md-2 col-sm-5 mt-md-2 col-4 offset-md-2 offset-lg-0">
        <button class="btn btn-edit ms-lg-4 me-lg-2 m-sm-0" @click="userUpdate">
          <img src="./../assets/user-pen.svg" alt="" />
        </button>
        <button class="btn btn-trash" data-bs-toggle="modal" data-bs-target="#exampleModal">
          <img src="./../assets/trash.svg" alt="" />
        </button>
      </div>
    </div>

    <div class="row mt-md-5">
      <div class="col-8 offset-3">
        <table class="table table-borderless">
          <tbody class="">
            <tr>
              <td class="fw-medium">ID</td>
              <td>{{ user.user_id }}</td>
            </tr>
            <tr>
              <td class="fw-medium">Name</td>
              <td>{{ user.user_name }}</td>
            </tr>
            <tr>
              <td class="fw-medium">Email</td>
              <td>{{ user.user_email }}</td>
            </tr>
            <tr>
              <td class="fw-medium">Phone</td>
              <td>{{ user.user_phone }}</td>
            </tr>
            <tr>
              <td class="fw-medium">City</td>
              <td>{{ user.user_city }}</td>
            </tr>
            <tr>
              <td class="fw-medium">Gender</td>
              <td>{{ user.user_gender }}</td>
            </tr>
            <tr>
              <td class="fw-medium">Address</td>
              <td>{{ user.user_address }}</td>
            </tr>
            <tr>
              <td class="fw-medium">Role</td>
              <td>{{ user.role_name }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
<script setup>
import router from '@/router'
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const user = ref({})
const storedToken = ref(localStorage.getItem('accessToken'))
onMounted(async () => {
  await getUser()
})
const getUser = async () => {
  try {
    const id = router.currentRoute.value.params.id
    const response = await fetch(`http://127.0.0.1:8000/api/users/${id}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })
    const data = await response.json()
    if (response.ok) {
      console.log(data.data)
      user.value = data.data
    } else {
      console.log('Error', response.message)
    }
  } catch (e) {
    console.log(e)
  }
}

const btnDelete = async () => {
  try {
    const response = await fetch(`http://127.0.0.1:8000/api/users/delete/${route.params.id}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })
    // const data = await response.json()
    if (response.ok) {
      console.log('Success')
      router.push('/users')
    } else {
      console.log('Error', response.message)
    }
  } catch (e) {
    console.log(e)
  }
}
const userUpdate = () => {
  router.push(`/users/update/${route.params.id}`)
}
const back = () => {
  router.push('/users')
}
</script>
<style scoped>
@import url('https://cdn-uicons.flaticon.com/2.1.0/uicons-regular-straight/css/uicons-regular-straight.css');
@import url('https://cdn-uicons.flaticon.com/2.1.0/uicons-regular-rounded/css/uicons-regular-rounded.css');
@import url('https://cdn-uicons.flaticon.com/2.1.0/uicons-bold-rounded/css/uicons-bold-rounded.css');

.backarrow {
  font-size: 30px;
  color: #343a40;
}
.backarrow:hover {
  color: #4da8f5;
}
#title {
  /* font-size: 32px; */
  color: #343a40 !important;
}
table {
  color: #343a40 !important;
}
.btn-edit {
  border: #4da8f5;
}
.btn-edit {
  border: 2px solid #74b9f3;
}

.btn-edit:hover {
  background-color: #c9e3f9;

  cursor: pointer;
}

.btn-trash {
  border: 2px solid #f65454;
}

.btn-trash:hover {
  background-color: #f08080;
}

#btnDelete {
  background-color: #f08080;
  color: #fff;
}
#btnDelete:hover {
  background-color: #f65454;
  color: #fff;
}
.btnBack:active {
  border: 2px solid #fff;
}
.trash {
  display: block;
  margin: auto;
  width: 100px;
  height: 100px;
}
/* Extra Small devices (portrait phones) */
@media only screen and (max-width: 575.98px) {
  /* CSS rules for extra small screens */
}
</style>
