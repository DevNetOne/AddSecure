<template>
  <v-data-table-server
      v-model:items-per-page="itemsPerPage"
      :headers="headers"
      :items-length="totalItems"
      :items="vehicles"
      :loading="loading"
      class="elevation-1"
      @update:options="getDataFromApi"
  >
    <template v-slot:top>
      <v-toolbar
          flat
      >
        <v-toolbar-title>Lista pojazdów</v-toolbar-title>
        <v-divider
            class="mx-4"
            inset
            vertical
        ></v-divider>
        <v-spacer></v-spacer>
        <v-dialog
            v-model="dialog"
            max-width="500px"
        >
          <template v-slot:activator="{ props }">
            <v-btn
                color="primary"
                dark
                class="mb-2"
                v-bind="props"
            >
              utwórz nowy pojazd
            </v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="text-h5">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text v-if="errorMessage">
              <v-alert
                  style="white-space: pre-line"
                  density="compact"
                  type="warning"
                  title="Alert title"
                  v-text="errorMessage"
              ></v-alert>
            </v-card-text>
            <v-card-text>
              <v-container>
                <v-row>
                  <v-col
                      cols="12"
                      sm="12"
                      md="12"
                  >
                    <v-text-field
                        v-model="editedItem.registrationNumber"
                        label="Numer rejestracyjny"
                    ></v-text-field>
                  </v-col>
                  <v-col
                      cols="12"
                      sm="12"
                      md="12"
                  >
                    <v-text-field
                        v-model="editedItem.carModel"
                        label="Model pojazdu"
                    ></v-text-field>
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn
                  color="blue-darken-1"
                  variant="text"
                  @click="close"
              >
                Anuluj
              </v-btn>
              <v-btn
                  color="blue-darken-1"
                  variant="text"
                  @click="save"
              >
                Zapisz
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog v-model="dialogDelete" max-width="500px">
          <v-card>
            <v-card-title class="text-h5">Jesteś pewien że chcesz usunąć ten rekord?</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue-darken-1" variant="text" @click="closeDelete">Anuluj</v-btn>
              <v-btn color="blue-darken-1" variant="text" @click="deleteItemConfirm">Usuń</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-icon
          size="small"
          class="me-2"
          @click="editItem(item.raw)"
      >
        mdi-pencil
      </v-icon>
      <v-icon
          size="small"
          @click="deleteItem(item.raw)"
      >
        mdi-delete
      </v-icon>
    </template>
    <template v-slot:no-data>
      <v-btn
          color="primary"
          @click="initialize"
      >
        Reset
      </v-btn>
    </template>
  </v-data-table-server>
</template>
<script>
import axios from "axios";

export default {
  data: () => ({
    defaultPage: 1,
    errorMessage: null,
    itemsPerPage: 2,
    dialog: false,
    dialogDelete: false,
    options: {},
    headers: [
      {
        title: 'Numer rejestracyjny',
        align: 'start',
        sortable: true,
        key: 'registrationNumber',
        value: 'registrationNumber'
      },
      { title: 'Model', key: 'carModel', value: 'carModel'},
      { title: 'Data utworzenia', key: 'createdAt', value: 'createdAt' },
      { title: 'Data aktualizacji', key: 'updatedAt', value: 'updatedAt' },
      { title: 'Akcje', key: 'actions', sortable: false },
    ],
    loading: true,
    totalItems: 0,
    vehicles: [],
    editedIndex: -1,
    editedItem: {
      id: 0,
      registrationNumber: '',
      carModel: '',
    },
    defaultItem: {
      registrationNumber: '',
      carModel: '',
    },
  }),

  computed: {
    formTitle () {
      return this.editedIndex === -1 ? 'Nowy pojazd' : 'Edytuj pojazd'
    },
  },
  watch: {
    dialog (val) {
      val || this.close()
    },
    dialogDelete (val) {
      val || this.closeDelete()
    },
  },
  methods: {
    getDataFromApi ({ page }) {
      this.loading = true
      this.getVehicles({ page });
    },
    async getVehicles ({ page }) {
      return  axios.get('http://localhost:9993/api/vehicles?page='+page, {
        headers: {
          'Content-Type': 'application/json'
        }
      })
          .then((response) => {
            this.vehicles = response.data['hydra:member']
            this.totalItems = response.data['hydra:totalItems']
            this.loading = false
          }).catch(function (error) {
            // handle error
            console.log(error);
          })
    },
    editItem (item) {
      this.editedIndex = this.vehicles.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialog = true
    },

    deleteItem (item) {
      this.editedIndex = this.vehicles.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialogDelete = true
    },

    deleteItemConfirm () {
      this.deleteVehicle()
      this.closeDelete()
    },

    close () {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },

    closeDelete () {
      this.dialogDelete = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
      this.getDataFromApi({page: this.defaultPage})
    },

    save () {
      if (this.editedIndex === -1) {
        this.createVehicle()
      } else {
        this.updateVehicle()
      }

      if (!this.errorMessage) {
        this.close()
        this.getDataFromApi({page: this.defaultPage})
      }
    },
    createVehicle() {
      this.errorMessage = null;
      axios.post('http://localhost:9993/api/vehicles',{
        registrationNumber: this.editedItem.registrationNumber,
        carModel: this.editedItem.carModel
      }, {
        headers: {
          'Content-Type': 'application/json'
        }
      }).catch((response) => {
        if (response.code === 'ERR_BAD_REQUEST' && response.response.status === 422) {
          this.errorMessage = response.response.data['hydra:description']
        }
      })
    },
    updateVehicle() {
      this.errorMessage = null;
      axios.patch('http://localhost:9993/api/vehicles/'+this.editedItem.id,{
        registrationNumber: this.editedItem.registrationNumber,
        carModel: this.editedItem.carModel
      }, {
        headers: {
          'Content-Type': 'application/merge-patch+json'
        }
      }).catch((response) => {
        if (response.code === 'ERR_BAD_REQUEST' && response.response.status === 422) {
          this.errorMessage = response.response.data['hydra:description']
        }
      })
    },
    deleteVehicle() {
      axios.delete('http://localhost:9993/api/vehicles/'+this.editedItem.id, {
        headers: {
          'Content-Type': 'application/json'
        }
      })
    }
  },
}
</script>