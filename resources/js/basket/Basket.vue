<template>
  <div>
    <success v-if="success">Congratulations on your purchase !</success>

    <div class="row" v-else>
      <div class="col-md-8" v-if="itemsInBasket">
        <div class="row mb-4">
          <div class="col-md-6">
            <label for="first_names">First Names</label>
            <input
              type="text"
              class="form-control"
              name="first_names"
              v-model="customer.first_names"
              :class="[
                {
                  'is-invalid': this.errorFor('customer.first_names'),
                },
              ]"
            />
            <v-errors :errors="errorFor('customer.first_names')"></v-errors>
          </div>
          <div class="col-md-6">
            <label for="last_name">Last Name</label>
            <input
              type="text"
              class="form-control"
              name="last_name"
              v-model="customer.last_name"
              :class="[
                {
                  'is-invalid': this.errorFor('customer.last_name'),
                },
              ]"
            />
            <v-errors :errors="errorFor('customer.last_name')"></v-errors>
          </div>
        </div>
        <div class="row mb-4">
          <div class="col-md-12">
            <label for="email">Email</label>
            <input
              type="text"
              class="form-control"
              name="email"
              v-model="customer.email"
              :class="[
                {
                  'is-invalid': this.errorFor('customer.email'),
                },
              ]"
            />
            <v-errors :errors="errorFor('customer.email')"></v-errors>
          </div>
        </div>
        <div class="row mb-4">
          <div class="col-md-6">
            <label for="street">Street</label>
            <input
              type="text"
              class="form-control"
              name="street"
              v-model="customer.street"
              :class="[
                {
                  'is-invalid': this.errorFor('customer.street'),
                },
              ]"
            />
            <v-errors :errors="errorFor('customer.street')"></v-errors>
          </div>
          <div class="col-md-6">
            <label for="city">City</label>
            <input
              type="text"
              class="form-control"
              name="city"
              v-model="customer.city"
              :class="[
                {
                  'is-invalid': this.errorFor('customer.city'),
                },
              ]"
            />
            <v-errors :errors="errorFor('customer.city')"></v-errors>
          </div>
        </div>

        <div class="row mb-4">
          <div class="col-md-6">
            <label for="country">Country</label>
            <input
              type="text"
              class="form-control"
              name="country"
              v-model="customer.country"
              :class="[
                {
                  'is-invalid': this.errorFor('customer.country'),
                },
              ]"
            />
            <v-errors :errors="errorFor('customer.country')"></v-errors>
          </div>

          <div class="col-md-4">
            <label for="state">State</label>
            <input
              type="text"
              class="form-control"
              name="state"
              v-model="customer.state"
              :class="[
                {
                  'is-invalid': this.errorFor('customer.state'),
                },
              ]"
            />
            <v-errors :errors="errorFor('customer.state')"></v-errors>
          </div>

          <div class="col-md-2">
            <label for="zip">Zip</label>
            <input
              type="text"
              class="form-control"
              name="zip"
              v-model="customer.zip"
              :class="[
                {
                  'is-invalid': this.errorFor('customer.zip'),
                },
              ]"
            />
            <v-errors :errors="errorFor('customer.zip')"></v-errors>
          </div>
        </div>

        <hr />

        <div class="row">
          <div class="col-md-12">
            <div class="d-grid">
              <button
                type="submit"
                class="btn btn-primary btn-lg"
                @click.prevent="book"
                :disabled="loading"
              >
                Book now !
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-8" v-else>
        <div class="jumbotron text-center">
          <h1>EMPTY</h1>
        </div>
      </div>

      <div class="col-md-4">
        <div class="d-flex justify-content-between">
          <h6 class="text-uppercase text-secondary fw-bolder">Your Cart</h6>
          <h6 class="badge rounded-pill bg-secondary text-uppercase">
            <span v-if="itemsInBasket"> Items {{ itemsInBasket }}</span>
            <span v-else>Empty</span>
          </h6>
        </div>

        <transition-group name="fade">
          <div v-for="item in basket" :key="item.bookable.id">
            <div class="pt-2 pb-2 border-top d-flex justify-content-between">
              <span>
                <router-link
                  to="{name: 'bookable' , params:{id: item.bookable.id}}"
                  >{{ item.bookable.title }}</router-link
                >
              </span>
              <span> {{ item.price.total }} TL </span>
            </div>

            <div class="pt-2 pb-2 d-flex justify-content-between">
              <span> From {{ item.dates.from }} </span>
              <span> To {{ item.dates.to }} </span>
            </div>

            <div class="pt-2 pb-2 text-end">
              <button
                class="btn btn-sm btn-outline-secondary"
                @click="$store.dispatch('removeFromBasket', item.bookable.id)"
              >
                <i class="fas fa-trash-alt"></i>
              </button>
            </div>
          </div>
        </transition-group>
      </div>
    </div>
  </div>
</template>


<script>
import { mapGetters, mapState } from "Vuex";
import validationErrors from "./../shared/mixins/validationErrors";

export default {
  mixins: [validationErrors],

  data() {
    return {
      loading: false,
      bookingAttemped: false,
      customer: {
        first_names: null,
        last_name: null,
        email: null,
        street: null,
        city: null,
        country: null,
        state: null,
        zip: null,
      },
    };
  },

  computed: {
    ...mapGetters(["itemsInBasket"]),
    ...mapState({
      basket: (state) => state.basket.items,
    }),

    success() {
      return !this.loading && 0 === this.itemsInBasket && this.bookingAttemped;
    },
  },

  methods: {
    async book() {
      this.loading = true;
      this.bookingAttemped = false;
      this.error = false;
      try {
        await axios.post(`/api/checkout`, {
          customer: this.customer,
          bookings: this.basket.map((basketItem) => ({
            bookable_id: basketItem.bookable.id,
            from: basketItem.dates.from,
            to: basketItem.dates.to,
          })),
        });
        this.$store.dispatch("clearBasket");
      } catch (error) {
        this.loading = false;
        this.errors = error.response && error.response.data.errors;
        return;
      }

      this.loading = false;
      this.bookingAttemped = true;
      console.log("buraya kadar geldi 2");
    },
  },
};
</script>

<style scoped>
h6.badge {
  font-size: 100%;
}

.jumbotron {
  padding: 2rem 1rem;
  margin-bottom: 2rem;
  background-color: rgb(238, 236, 236);
  border-radius: 0.3rem;
}
</style>
