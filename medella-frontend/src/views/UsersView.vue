<template>
  <div class="container">
    <div class="row mt-lg-3 mt-md-2 mt-sm-2 mb-sm-3">
      <div class="col-lg-4 col-md-4 col-sm-12 offset-lg-1 offset-md-1 h3" alt="User List Heading">
        User List
      </div>

      <div class="col-lg-3 col-md-6 offset-md-0 col-sm-6 col-10 offset-lg-3 mb-3">
        <div class="search-container mt-sm-3">
          <form @submit.prevent="onFormSubmit">
            <input
              type="text"
              class="form-control form-control-sm"
              id="searchInput"
              v-model="searchKeyword"
              @change="handleKeyDown"
              placeholder="Search by name, email, and phone"
            />
            <button
              class="btn btn-md px-2 actionBtn ms-1"
              type="submit"
              id="searchButton"
              alt="search button"
            >
              <img src="./../assets/search.svg" alt="" />
            </button>
          </form>
          <button class="btn px-3 ms-2 actionBtn" alt="add button" @click="createUser">
            <div class="d-flex">
              <img src="./../assets/plus.svg" class="me-1" alt="" />
              <span style="color: #fff">Add</span>
            </div>
          </button>
        </div>
      </div>
      <!-- <div class="col-md-1"></div> -->
    </div>
    <div class="row mt-lg-4 mt-md-4 mt-sm-5">
      <div class="offset-lg-1 col-lg-11 col-md-12 col-12 col-sm-12">
        <div class="card shadow-sm overflow-x-auto">
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th class="id-column">#</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody class="table-group-divider">
                <tr v-for="user in userList.data" :key="user.id">
                  <td class="id-column">{{ user.id }}</td>
                  <td>{{ user.name }}</td>
                  <td>{{ user.email }}</td>
                  <td>{{ user.phone }}</td>
                  <td>
                    <button class="btn btnDetail btn-sm" @click="getDetails(user.id)">
                      <i class="fi fi-rs-eye"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
              <tfoot>
                <nav aria-label="Page navigation example">
                  <ul class="pagination">
                    <li class="page-item" :class="{ disabled: currentPage === 1 }">
                      <a class="page-link" href="#" aria-label="Previous" @click="prevPage">
                        <span aria-hidden="true">&laquo;</span>
                      </a>
                    </li>
                    <li
                      class="page-item"
                      v-for="page in lastPage"
                      :key="page"
                      :class="{ active: currentPage === page }"
                    >
                      <a class="page-link" href="#" @click="changePage(page)">{{ page }}</a>
                    </li>
                    <li class="page-item" :class="{ disabled: currentPage === lastPage }">
                      <a class="page-link" href="#" aria-label="Next" @click="nextPage">
                        <span aria-hidden="true">&raquo;</span>
                      </a>
                    </li>
                  </ul>
                </nav>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
      <!-- <div class="col-1"></div> -->
    </div>
    <!-- <ul>
      <li v-for="user in userList" :key="user.id">{{ user.name }}</li>
    </ul> -->
  </div>
</template>

<script setup>
import router from '@/router'
import { ref, onMounted } from 'vue'

const userList = ref([])
const storedToken = ref(localStorage.getItem('accessToken'))
const currentPage = ref(1)
const lastPage = ref()
const paginationLink = ref([])

const searchKeyword = ref('')

onMounted(async () => {
  await getUserList()
})
const handleKeyDown = () => {
  if (searchKeyword.value === '') {
    onFormSubmit()
  }
}
const getUserList = async () => {
  try {
    const response = await fetch(`http://127.0.0.1:8000/api/users?page=${currentPage.value}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })
    const data = await response.json()
    if (response.ok) {
      userList.value = data.data
      currentPage.value = data.data.current_page
      lastPage.value = data.data.last_page
      paginationLink.value = data.data.links
    } else {
      console.log('Error', data.message)
    }
  } catch (error) {
    console.error('Error:', error)
  }
}

//get all user end

//search functions start
const getSearchedUserList = async () => {
  console.log('>>>>>>>>>>>>>>>>>>>>>>here')
  const formData = new FormData()
  formData.append('keyword', searchKeyword.value)
  console.log(searchKeyword.value)

  try {
    const response = await fetch(
      `http://127.0.0.1:8000/api/activate-users/search?page=${currentPage.value}`,
      {
        method: 'POST',
        headers: {
          Authorization: `Bearer ${storedToken.value}`
        },
        body: formData
      }
    )

    if (response.ok) {
      const data = await response.json()
      userList.value = data.data
      currentPage.value = data.data.current_page
      lastPage.value = data.data.last_page
      paginationLink.value = data.data.links
    } else {
      console.log('Error:', response.message)
    }
  } catch (e) {
    console.log(e)
  }
}
const onFormSubmit = () => {
  if (searchKeyword.value.trim() === '') {
    getUserList()
  } else {
    getSearchedUserList()
  }
}
// search functions end

//details function start
const getDetails = (param) => {
  console.log('Parameter:', param)
  router.push({
    path: `/users/${param}`
    // props: { userId: param }
  })
}
const createUser = () => {
  router.push('create-user')
}

//details function end

const changePage = async (page) => {
  currentPage.value = page
  await getUserList()
}

const nextPage = async () => {
  if (currentPage.value < lastPage.value) {
    currentPage.value++
    await getUserList()
  }
}

const prevPage = async () => {
  if (currentPage.value > 1) {
    currentPage.value--
    await getUserList()
  }
}
</script>

<style scoped>
* {
  color: #343a40;
}
.search-container,
.search-container form {
  /* width: 350px; */
  display: flex;
}
#searchInput {
  width: 280px;
  color: #343a40;
}
#searchInput::placeholder {
  color: #b0bec5;
}
.text-button {
  display: flex;
  align-items: center;
  justify-content: center;
}

.actionBtn {
  background-color: #74b9f3;
  border: 0;
  color: #fff;
}
.actionBtn:hover {
  background-color: #4da8f5;
  color: #fff;
  cursor: pointer;
}

.btnDetail {
  border: 2px solid #c9e3f9;
}

.btnDetail:hover {
  background-color: #74b9f3;

  cursor: pointer;
}
.btnDetail:active {
  border: 2px solid #74b9f3;
}
.page-item {
  color: #343a40;
}
.page-link:active {
  background-color: #fff;
  /* color: ; */
}
.page-link,
.page-link span {
  text-decoration: none;
  color: #343a40;
}
.page-link:hover,
.page-link span:hover {
  color: #74b9f3;
  cursor: pointer;
}
.id-column {
  width: 50px !important;
}
.table {
  padding-right: 10px;
}
/* Extra Small devices (portrait phones) */
@media only screen and (max-width: 575.98px) {
  /* CSS rules for extra small screens */
  #searchInput {
    width: 250px;
    color: #343a40;
  }
  #searchInput::placeholder {
    font-size: 14px;
  }
}

/* Small devices (landscape phones, 576px and up) */
@media only screen and (min-width: 576px) and (max-width: 767.98px) {
  /* CSS rules for small screens */
}

/* Medium devices (tablets, 768px and up) */
@media only screen and (min-width: 768px) and (max-width: 991.98px) {
  /* CSS rules for medium screens */
  .id-column {
    width: 30px !important;
  }
}

/* Large devices (desktops, 992px and up) */
@media only screen and (min-width: 992px) {
  /* CSS rules for large screens */
}

.alert-success {
  background-color: #4da7f535;
  color: #343a40;
}
.alert-error {
  background-color: #f3909078;
  color: #343a40;
}
.table {
  overflow-x: auto !important;
}
</style>
