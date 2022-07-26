<template>
  <div class="d-flex w-50 m-auto align-items-center">
    <div class="card card-body">
      <form action="">
        <div class="form-group m-3">
          <label for="email">E-mail</label>
          <input
            type="text"
            name="email"
            placeholder="Enter your e-mail"
            class="form-control"
            v-model="email"
            :class="[
              {
                'is-invalid': this.errorFor('email'),
              },
            ]"
          />
          <v-errors :errors="errorFor('email')"></v-errors>
        </div>

        <div class="form-group m-3">
          <label for="password">Password</label>
          <input
            type="password"
            name="password"
            placeholder="Enter your password"
            class="form-control"
            v-model="password"
            :class="[
              {
                'is-invalid': this.errorFor('password'),
              },
            ]"
          />
          <v-errors :errors="errorFor('password')"></v-errors>
        </div>
        <div class="d-grid m-3 pt-1">
          <button
            type="submit"
            class="btn btn-primary btn-lg btn-block"
            :disabled="loading"
            @click.prevent="login"
          >
            Log-in
          </button>
        </div>

        <hr />
        <div>
          No account yet ?
          <router-link :to="{name:'register'}" class="fw-bold text-primary"
            >Register</router-link
          >
        </div>

        <div>
          Forgotten password ?
          <router-link :to="{name:'home'}" class="fw-bold text-primary"
            >Reset password</router-link
          >
        </div>
      </form>
    </div>
  </div>
</template>


<script>
import validationErrors from "../shared/mixins/validationErrors";
import { logIn } from "../shared/utils/auth";

export default {
  mixins: [validationErrors],

  data() {
    return {
      email: null,
      password: null,
      loading: false,
    };
  },

  methods: {
    async login() {
      this.loading = true;
      this.errors = null;
      try {
        await axios.get("/sanctum/csrf-cookie");
        await axios.post("/login", {
          email: this.email,
          password: this.password,
        });

        logIn();
        this.$store.dispatch("loadUser");
        this.$router.push({name: "home"});

      } catch (error) {
        this.errors = error.response && error.response.data.errors;
      }

      this.loading = false;
    },
  },
};
</script>
