<template>
  <div>
    <h6 class="text-uppercase text-secondary fw-bolder">price breakdown</h6>

    <div
      v-for="(days, price) in price.breakdown"
      :key="price"
      class="pt-2 pb-2 border-bottom border-top d-flex justify-content-between"
    >
      <span>{{ days }} x {{ price }} TL</span>
      <span> {{ days * price }} TL</span>
    </div>

    <div class="fw-bolder d-flex justify-content-between">
      <span> Total</span>
      <span>{{ price.total }} TL</span>
    </div>
  </div>
</template>


<script>
export default {
  props: {
    price: Object,
  },
};
</script>
