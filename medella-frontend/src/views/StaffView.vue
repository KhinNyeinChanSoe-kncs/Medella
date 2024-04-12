<template>
  <div>
    <div class="row mt-lg-4 mt-md-4 mt-sm-2">
      <div
        class="col-lg-2 col-md-4 col-sm-6 offset-lg-1 offset-md-1 col-4 h3"
        alt="User List Heading"
      >
        Staff List
      </div>

      <div class="col-lg-3 col-md-6 offset-md-1 offset-0 col-sm-6 col-8 offset-lg-5 mb-3">
        <div class="search-container">
          <form @submit.prevent="onFormSubmit">
            <input
              type="text"
              class="form-control form-control-md"
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
        </div>
      </div>
      <!-- <div class="col-md-1"></div> -->
    </div>
    <div class="row mt-lg-4 mt-md-4 mt-sm-2">
      <div class="offset-lg-1 col-lg-11 col-md-12 col-12 col-sm-12">
        <div class="card shadow-sm overflow-x-auto mx-3">
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Name</th>
                  <th scope="col">Email</th>
                  <th scope="col">Phone</th>
                  <th scope="col">Department</th>
                  <th scope="col">Role</th>
                </tr>
              </thead>
              <tbody class="table-group-divider">
                <tr v-for="(user, index) in userList.data" :key="index">
                  <td class="id-column">{{ ++index }}</td>
                  <td>{{ user.name }}</td>
                  <td>{{ user.email }}</td>
                  <td>{{ user.phone }}</td>
                  <td>{{ user.department_name }}</td>
                  <td>{{ user.role_name }}</td>
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
      <div class="col-1"></div>
    </div>
    <!-- <ul>
      <li v-for="user in userList" :key="user.id">{{ user.name }}</li>
    </ul> -->
  </div>
</template>

<script setup>
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
//get all user start
const getUserList = async () => {
  try {
    const response = await fetch(
      `http://127.0.0.1:8000/api/staff-users?page=${currentPage.value}`,
      {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${storedToken.value}`
        }
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
//get all user end

//search functions start
const getSearchedUserList = async () => {
  const formData = new FormData()
  formData.append('keyword', searchKeyword.value)
  console.log(searchKeyword.value)

  try {
    const response = await fetch(
      `http://127.0.0.1:8000/api/search/staff-users?page=${currentPage.value}`,
      {
        method: 'POST',
        headers: {
          Accept: 'application/json',
          Authorization: `Bearer ${storedToken.value}`
        },
        body: formData
      }
    )

    if (response.ok) {
      const data = await response.json()
      userList.value = data.data
      console.log('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>', data.message)

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
  background-color: #4da8f5;
  border: 0;
  color: #343a40;
}

.actionBtn:hover {
  background-color: #74b9f3;
  color: #343a40;
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
  width: 20px !important;
}
@media only screen and (max-width: 575.98px) {
  /* CSS rules for extra small screens */
  #searchInput {
    width: 230px;
    color: #343a40;
  }
  #searchInput::placeholder {
    font-size: 14px;
  }
}
</style>
