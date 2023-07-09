<template>
  <v-menu
    :close-on-content-click="false"
    location="bottom"
  >
    <template v-slot:activator="{ props }">
      <v-btn
        v-bind="props"
        color="primary"
        variant="text"
        icon="mdi-magnify"
        class="order-0 order-md-1"
      />
    </template>
  </v-menu>
</template>
