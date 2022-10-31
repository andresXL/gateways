<template>
  <q-layout view="lHh Lpr lFf">
    <q-header class="bg-transparent">
      <q-toolbar>
        <q-avatar :size="$q.screen.gt.sm ? '150px' : '100px'">
          <q-img :src="require('src/assets/images/musala-logo.png')" />
        </q-avatar>

        <q-toolbar-title class="text-primary text-weight-medium">
          Gateways <br v-if="$q.screen.lt.md" />
          project
        </q-toolbar-title>
      </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'MainLayout',

  components: {},

  setup() {
    return {};
  },
});
</script>
