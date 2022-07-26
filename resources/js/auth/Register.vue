<template>
  <div class="d-flex w-50 m-auto align-items-center">
    <div class="card card-body">
      <form action="">
        <div class="form-group m-3">
          <label for="name">Name</label>
          <input
            type="text"
            name="name"
            placeholder="Enter your name"
            class="form-control"
            v-model="user.name"
            :class="[
              {
                'is-invalid': this.errorFor('name'),
              },
            ]"
          />
          <v-errors :errors="errorFor('name')"></v-errors>
        </div>

        <div class="form-group m-3">
          <label for="email">E-mail</label>
          <input
            type="text"
            name="email"
            placeholder="Enter your e-mail"
            class="form-control"
            v-model="user.email"
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
            v-model="user.password"
            :class="[
              {
                'is-invalid': this.errorFor('password'),
              },
            ]"
          />
          <v-errors :errors="errorFor('password')"></v-errors>
        </div>

        <div class="form-group m-3">
          <label for="password_confirmation">Re-type Password</label>
          <input
            type="password"
            name="password_confirmation"
            placeholder="Confirm your password"
            class="form-control"
            v-model="user.password_confirmation"
            :class="[
              {
                'is-invalid': this.errorFor('password_confirmation'),
              },
            ]"
          />
          <v-errors :errors="errorFor('password_confirmation')"></v-errors>
        </div>

        <div class="d-grid m-3 pt-1">
          <button
            type="submit"
            class="btn btn-primary btn-lg btn-block"
            :disabled="loading"
            @click.prevent="register"
          >
            Register
          </button>
        </div>

        <hr />
        <div>
          Already have an account?
          <router-link :to="{name:'login'}" class="fw-bold text-primary"
            >Login</router-link
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
      user: {
        name: null,
        email: null,
        password: null,
        password_confirmation:null,
      },

      loading: false,
    };
  },

  methods: {
    async register() {
      this.loading = true;
      this.errors = null;
      try {
       const response =  await axios.post("/register", this.user);

        if(201 ==response.status){
        logIn();
        this.$store.dispatch("loadUser");
        this.$router.push({ name: "home" });
        }

      } catch (error) {
        this.errors = error.response && error.response.data.errors;
      }

      this.loading = false;
    },
  },
};
</script>
