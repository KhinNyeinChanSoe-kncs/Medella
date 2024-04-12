<template>
  <div>
    <div class="row mt-lg-4 mt-md-4 mt-sm-3 mt-3">
      <div
        class="col-lg-3 col-md-6 col-sm-8 offset-lg-1 offset-md-1 col-8 h3"
        alt="User List Heading"
      >
        Medical Records
      </div>

      <div
        class="col-lg-2 col-md-2 offset-md-3 offset-0 col-sm-2 offset-sm-2 col-4 offset-lg-5 mb-3"
      >
        <form action="">
          <select
            class="form-select form-select-sm"
            aria-label="Small select example"
            @change="filter"
            v-model="selectedFilter"
            v-show="user.role_id !== 4"
          >
            <option value="all">All</option>
            <option value="ipd">IPD</option>
            <option value="opd">OPD</option>
          </select>
        </form>
      </div>
    </div>
    <div class="row mt-lg-4 mt-md-4 mt-sm-2 mt-3">
      <div class="offset-lg-1 col-lg-11 col-md-12 col-12 col-sm-12">
        <div class="card shadow-sm overflow-x-auto">
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Patient</th>
                  <th scope="col">Doctor</th>
                  <th scope="col">Disease</th>
                  <th scope="col">Type</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody class="table-group-divider">
                <tr v-for="record in recordList" :key="record.medical_record_id">
                  <td class="id-column">{{ record?.medical_record_id }}</td>
                  <td>{{ record?.patient_name }}</td>
                  <td>{{ record?.doctor_name }}</td>
                  <td>{{ record?.disease }}</td>
                  <td>{{ record.inpatient_status === 0 ? 'OPD' : 'IPD' }}</td>
                  <td>
                    <button
                      class="btn btnDetail btn-sm me-2"
                      @click="getDetails(record.medical_record_id)"
                    >
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
      <div class="col-1"></div>
    </div>
  </div>
</template>

<script setup>
import router from '@/router'
import { ref, onMounted } from 'vue'
const selectedFilter = ref('all')
const recordList = ref([])
const storedToken = ref(localStorage.getItem('accessToken'))
const user = ref({})
const currentPage = ref(1)
const lastPage = ref()
const paginationLink = ref([])
// const searchKeyword = ref('')

onMounted(async () => {
  await filter()
  await getUser()
})
const getDetails = (param) => {
  router.push(`/medical-records/${param}`)
}
const getUser = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/me', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })
    const data = await response.json()
    if (response.ok) {
      user.value = data.data
      // if (user.value.role_id === 4) {
      // }
      console.log('USER INFORMATION: ', user.value)
    } else {
      console.log('error', data.message)
    }
  } catch (e) {
    console.log(e)
  }
}

const filter = async () => {
  const formData = new FormData()
  formData.append('status', selectedFilter.value)
  console.log('filter value>>>', selectedFilter.value)
  try {
    const response = await fetch(
      `http://127.0.0.1:8000/api/medical-records/filter?page=${currentPage.value}`,
      {
        method: 'POST',
        headers: {
          Accept: 'application/json',
          Authorization: `Bearer ${storedToken.value}`
        },
        body: formData
      }
    )
    const data = await response.json()
    if (response.ok) {
      recordList.value = data.data.data
      currentPage.value = data.data.current_page
      lastPage.value = data.data.last_page
      paginationLink.value = data.data.links

      console.log('response data', data.data)
    } else {
      console.log('Error:', data.message)
    }
  } catch (e) {
    console.log(e)
  }
}

const changePage = async (page) => {
  currentPage.value = page
  await filter()
}

const nextPage = async () => {
  if (currentPage.value < lastPage.value) {
    currentPage.value++
    await filter()
  }
}

const prevPage = async () => {
  if (currentPage.value > 1) {
    currentPage.value--
    await filter()
  }
}
</script>

<style scoped>
* {
  color: #343a40;
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
</style>
