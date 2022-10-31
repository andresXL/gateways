<template>
  <q-page padding>
    <div class="row full-width justify-center">
      <div class="col-md-9 col-xs-11 col-lg-8">
        <my-table
          :actions="actions"
          :loading="loading"
          name="Peripherals"
          :rows="rows"
          :columns="columns"
          @on-create="showDialog = true"
          @on-go-offline="onGoOffline"
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
import { Peripheral } from '../Models/Peripheral';

export default defineComponent({
  name: 'PageIndex',
  components: { MyTable },
  props: {
    id: String,
  },
  setup(props) {
    const columns = ref([
      {
        name: 'id',
        field: 'id',
        label: 'ID',
        align: 'center',
        sortable: true,
      },
      {
        name: 'vendor',
        field: 'vendor',
        label: 'Vendor',
        align: 'center',
        sortable: true,
      },
      {
        name: 'status',
        field: 'status',
        label: 'status',
        align: 'center',
        sortable: true,
      },
      {
        name: 'created_at',
        field: 'created_at',
        label: 'Created at',
        align: 'center',
        sortable: true,
      },
    ]);

    const loading = ref(true);

    const rows = ref<Array<Peripheral>>([]);

    function onList() {
      axios
        .get('peripherals', {
          params: {
            gateway: props.id as string,
          },
        })
        .then((response) => {
          rows.value = response.data as Peripheral[];
          for (let i = 0; i < rows.value.length; i++) {
            const tmp = new Date(rows.value[i].created_at);
            const item = `${tmp.getDate()}/${
              tmp.getMonth() + 1
            }/${tmp.getFullYear()}`;
            rows.value[i].created_at = item;
          }
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
      vendor: '',
    });

    const showDialog = ref(false);

    const addingLoading = ref(false);

    function onAdd() {
      addingLoading.value = true;
      axios
        .post('peripheral/store', {
          gateway_id: props.id,
          ...formObject.value,
        })
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
      formObject.value.vendor = '';
      showDialog.value = false;
    }

    function onGoOffline(event: any) {
      loading.value = true;
      const x: string = event.id as string;
      axios
        .put('peripheral/' + x, {
          status: 'off',
        })
        .then((response) => {
          Notify.create({
            color: 'positive',
            icon: 'done',
            message: 'Edited succefully',
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
          onClean();
        });
    }

    return {
      columns,
      rows,
      loading,
      showDialog,
      formObject,
      onClean,
      onAdd,
      addingLoading,
      actions: [
        {
          icon: 'manage_accounts',
          emit_text: 'on-go-offline',
          tooltip: 'Put inactive',
        },
      ],
      onGoOffline,
    };
  },
});
</script>
