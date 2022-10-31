<template>
  <q-page padding>
    <div class="row full-width justify-center">
      <div class="col-md-9 col-xs-11 col-lg-8">
        <my-table
          :loading="loading"
          name="Gateways"
          :rows="rows"
          :columns="columns"
          :actions="actions"
          @on-create="showDialog = true"
          @on-go-peripherals="
            $router.push({
              name: 'peripherals',
              params: {
                id: $event.id,
              },
            })
          "
        >
        </my-table>
      </div>
    </div>
    <q-dialog v-model="showDialog" persistent>
      <q-card style="width: 300px">
        <q-bar>
          Gateway
          <q-space />
          <q-btn
            dense
            flat
            icon="close"
            v-close-popup
            @click="onClean()"
            :loading="addingLoading"
          />
        </q-bar>
        <q-card-section class="q-pt-lg">
          <div
            class="q-mb-md"
            v-for="(item, iter) in Object.keys(formObject)"
            :key="`item${iter}`"
          >
            <q-input
              dense
              :label="$t(item)"
              outlined
              v-model="formObject[item]"
              color="primary"
            />
          </div>
        </q-card-section>
        <q-card-actions class="q-px-md q-pt-xs">
          <q-btn
            label="Cancel"
            @click="onClean()"
            no-caps
            :loading="addingLoading"
          />
          <q-space />
          <q-btn
            label="Add"
            color="primary"
            no-caps
            :loading="addingLoading"
            @click="onAdd()"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script lang="ts">
import axios from 'axios';
import { Notify } from 'quasar';
import MyTable from 'src/components/MyTable.vue';
import { defineComponent, onMounted, ref } from 'vue';
import { Gateway } from '../Models/Gateway';

export default defineComponent({
  name: 'PageIndex',
  components: { MyTable },
  setup() {
    const columns = ref([
      {
        name: 'id',
        field: 'id',
        label: 'ID',
        align: 'center',
        sortable: true,
      },
      {
        name: 'serial_number',
        field: 'serial_number',
        label: 'Serial number',
        align: 'center',
        sortable: true,
      },
      {
        name: 'name',
        field: 'name',
        label: 'Name',
        align: 'center',
        sortable: true,
      },
      {
        name: 'ipv4_address',
        field: 'ipv4_address',
        label: 'IPv4',
        align: 'center',
      },
    ]);

    const loading = ref(true);

    const rows = ref<Array<Gateway>>([]);

    function onList() {
      axios
        .get('gateways')
        .then((response) => {
          rows.value = response.data as Gateway[];
        })
        .then((error) => {
          console.log(error);
        })
        .finally(() => {
          loading.value = false;
        });
    }

    onMounted(onList);

    const formObject = ref({
      name: '',
      serial_number: '',
      ipv4_address: '',
    });

    const showDialog = ref(false);

    const addingLoading = ref(false);

    function onAdd() {
      addingLoading.value = true;
      axios
        .post('gateway/store', formObject.value)
        .then((response) => {
          Notify.create({
            color: 'positive',
            icon: 'done',
            message: 'Added succefully',
          });
          onList();
        })
        .catch((error) => {
          Notify.create({
            color: 'negative',
            icon: 'warning',
            message: 'There was an error',
          });
        })
        .finally(() => {
          loading.value = false;
          addingLoading.value = false;
          onClean();
        });
    }

    function onClean() {
      formObject.value.name = '';
      formObject.value.serial_number = '';
      formObject.value.ipv4_address = '';
      showDialog.value = false;
    }

    return {
      columns,
      rows,
      loading,
      actions: [
        {
          icon: 'trending_up',
          emit_text: 'on-go-peripherals',
          tooltip: 'Go to peripherals',
        },
      ],
      showDialog,
      formObject,
      onClean,
      onAdd,
      addingLoading,
    };
  },
});
</script>
