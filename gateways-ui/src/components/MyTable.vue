<template>
  <q-btn
    v-if="creation"
    label="Create"
    color="primary"
    class="q-mb-md q-px-xl q-py-sm"
    no-caps
    @click="$emit('on-create')"
    rounded
  />
  <q-table
    :loading="loading"
    :title="name"
    :rows="rows"
    :columns="columns"
    separator="cell"
    :rows-per-page-options="[5]"
    :loading-label="'Loading data'"
    :no-results-label="'No data to show'"
    :no-data-label="'No data to show'"
    :filter="filter"
  >
    <template v-slot:top-right>
      <q-input
        borderless
        dense
        debounce="300"
        v-model="filter"
        placeholder="Search"
      >
        <template v-slot:append>
          <q-icon name="search" />
        </template>
      </q-input>
    </template>
    <template v-slot:header="props">
      <q-tr :props="props">
        <q-th v-for="col in props.cols" :key="col.name" :props="props">
          <span>{{ col.label }}</span>
        </q-th>
        <q-th auto-width v-if="actions"> Actions </q-th>
      </q-tr>
    </template>
    <template v-slot:body="props">
      <q-tr :props="props">
        <q-td v-for="col in props.cols" :key="col.name" :props="props">
          {{ col.value }}
        </q-td>
        <q-td auto-width v-if="actions">
          <q-btn
            v-for="(action, iter) in actions"
            :key="`action-${iter}`"
            flat
            size="sm"
            round
            color="primary"
            @click="$emit(action.emit_text, props.row)"
            :icon="action.icon"
          >
            <q-tooltip v-if="action.tooltip">
              {{ action.tooltip }}
            </q-tooltip>
          </q-btn>
        </q-td>
      </q-tr>
    </template>
  </q-table>
</template>

<script lang="ts">
import { defineComponent, PropType, ref } from 'vue';

export default defineComponent({
  name: 'ItemCard',

  components: {},
  props: {
    rows: {
      type: Object as PropType<Array<any>>,
      required: true,
    },
    columns: {
      type: Object as PropType<Array<any>>,
      required: true,
    },
    name: {
      type: String,
      required: true,
    },
    creation: {
      type: Boolean,
      default: true,
    },
    loading: Boolean,
    actions: Object as PropType<Array<any>>,
  },
  setup() {
    const filter = ref('');
    return {
      filter,
    };
  },
});
</script>
