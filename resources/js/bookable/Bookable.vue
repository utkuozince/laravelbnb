<template>
  <div class="row">
    <div class="col-md-8 pb-4">
      <div class="card">
        <div class="card-body">
          <div v-if="!loading">
            <h2>{{ bookable.title }}</h2>
            <hr />
            <article>{{ bookable.description }}</article>
          </div>
          <div v-else>Loading...</div>
        </div>
      </div>
      <review-list :bookable-id="this.$route.params.id"></review-list>
    </div>
    <div class="col-md-4 pb-4">
      <availability
        class="mb-4"
        :bookable-id="this.$route.params.id"
        @availability="checkPrice($event)"
      ></availability>

      <transition name="fade">
        <price-breakdown
          v-if="price"
          :price="price"
          class="mb-4"
        ></price-breakdown>
      </transition>

      <div class="d-grid">
        <transition name="fade">
          <button
            class="btn btn-outline-secondary btn-block"
            @click="addToBasket"
            v-if="price"
            :disabled="inBasketAlready"
          >
            Book Now
          </button>
        </transition>
      </div>

      <div class="d-grid mt-3">
          <button
            class="btn btn-outline-secondary btn-block"
            @click="removeFromBasket"
            v-if="inBasketAlready"
          >
            Remove From Basket
          </button>

      </div>

      <div v-if="inBasketAlready" class="mt-4 text-muted warning">
        Seems like you've added this object to basket already. If you want to
        change dates , remove from the basket first.
      </div>
    </div>
  </div>
</template>

<script>
import Availability from "./Availability";
import ReviewList from "./ReviewList";
import PriceBreakdown from "./PriceBreakdown";
import { mapState } from "Vuex";

export default {
  components: {
    Availability,
    ReviewList,
    PriceBreakdown,
  },

  data() {
    return {
      bookable: null,
      loading: false,
      price: null,
    };
  },

  created() {
    this.loading = true;
    console.log(this.$route.params.id);
    axios.get(`/api/bookables/${this.$route.params.id}`).then((response) => {
      this.bookable = response.data.data;
      this.loading = false;
    });
  },

  computed: {
    ...mapState({
    lastSearch: "lastSearch",
  }),

  inBasketAlready(){
     if (null == this.bookable) {
        return false;
      }

      return this.$store.getters.inBasketAlready(this.bookable.id);
  },

  },

  methods: {
    async checkPrice(hasAvailability) {
      if (!hasAvailability) {
        this.price = null;
        return;
      }

      try {
        this.price = (
          await axios.get(
            `/api/bookables/${this.bookable.id}/price?from=${this.lastSearch.from}&to=${this.lastSearch.to}`
          )
        ).data.data;
      } catch (err) {
        this.price = null;
      }
    },

    addToBasket() {
      this.$store.dispatch("addToBasket", {
        bookable: this.bookable,
        price: this.price,
        dates: this.lastSearch,
      });
    },

    removeFromBasket(){
        this.$store.dispatch("removeFromBasket",this.bookable.id)
    }
  },
};
</script>

<style scoped>
.warning {
  font-size: 0.7rem;
}
</style>
