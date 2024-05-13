<script setup lang="ts">
import { Head, Link, usePage } from "@inertiajs/vue3";
import { onMounted } from "vue";

const { props } = usePage();
const user = props?.auth?.user;

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    laravelVersion: string;
    phpVersion: string;
}>();

</script>

<template>
    <Head title="Welcome" />
    <header>
        <div class="navbar bg-base-100">
            <div class="flex-1">
                <a class="btn btn-ghost text-xl">daisyUI</a>
            </div>

            <nav v-if="canLogin" class="navbar-end gap-2">
                <Link v-if="user" :href="route('dashboard')" class="btn">
                    Dashboard
                </Link>
                <template v-else>
                    <Link :href="route('login')" class="btn"> Log in </Link>
                    <Link v-if="canRegister" :href="route('register')" class="btn">  Register  </Link>
                    <Link :href="route('admin.login')" class="btn"> admin/Log in </Link>
                    <Link v-if="canRegister" :href="route('admin.register')" class="btn">  admin/Register  </Link>
                    <Link :href="route('todo.index')" class="btn"> Todo </Link>
                    <Link :href="route('product.index')" class="btn"> EC </Link>
                    <Link :href="route('admin.product.index')" class="btn"> EC </Link>
                    <Link :href="route('contact.create')" class="btn"> contact </Link>
                </template>
            </nav>
        </div>
    </header>

    <main class="mt-6">
      <div class="hero min-h-screen bg-base-200">
        <div class="hero-content flex-col lg:flex-row">
          <img src="https://daisyui.com/images/stock/photo-1635805737707-575885ab0820.jpg" class="max-w-sm rounded-lg shadow-2xl" />
          <div>
            <h1 class="text-5xl font-bold">Box Office News!</h1>
            <p class="py-6">Provident cupiditate voluptatem et in. Quaerat fugiat ut assumenda excepturi exercitationem quasi. In deleniti eaque aut repudiandae et a id nisi.</p>
            <button class="btn btn-primary">Get Started</button>
          </div>
        </div>
      </div>

      <div class="carousel rounded-box">
        <div class="carousel-item">
          <img src="https://daisyui.com/images/stock/photo-1559703248-dcaaec9fab78.jpg" alt="Burger" />
        </div> 
        <div class="carousel-item">
          <img src="https://daisyui.com/images/stock/photo-1565098772267-60af42b81ef2.jpg" alt="Burger" />
        </div> 
        <div class="carousel-item">
          <img src="https://daisyui.com/images/stock/photo-1572635148818-ef6fd45eb394.jpg" alt="Burger" />
        </div> 
        <div class="carousel-item">
          <img src="https://daisyui.com/images/stock/photo-1494253109108-2e30c049369b.jpg" alt="Burger" />
        </div> 
        <div class="carousel-item">
          <img src="https://daisyui.com/images/stock/photo-1550258987-190a2d41a8ba.jpg" alt="Burger" />
        </div> 
        <div class="carousel-item">
          <img src="https://daisyui.com/images/stock/photo-1559181567-c3190ca9959b.jpg" alt="Burger" />
        </div> 
        <div class="carousel-item">
          <img src="https://daisyui.com/images/stock/photo-1601004890684-d8cbf643f5f2.jpg" alt="Burger" />
        </div>
      </div>

      <div class="hero min-h-screen" style="background-image: url(https://daisyui.com/images/stock/photo-1507358522600-9f71e620c44e.jpg);">
        <div class="hero-overlay bg-opacity-60"></div>
        <div class="hero-content text-center text-neutral-content">
          <div class="max-w-md">
            <h1 class="mb-5 text-5xl font-bold">Hello there</h1>
            <p class="mb-5">Provident cupiditate voluptatem et in. Quaerat fugiat ut assumenda excepturi exercitationem quasi. In deleniti eaque aut repudiandae et a id nisi.</p>
            <button class="btn btn-primary">Get Started</button>
          </div>
        </div>
      </div>
    </main>

    <footer class="footer p-10 bg-neutral text-neutral-content">
        <aside>
            ロゴ
            <p>Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})</p>
        </aside>
        <nav>
            <h6 class="footer-title">Social</h6>
            <div class="grid grid-flow-col gap-4">
                <a>アイコン</a>
                <a>アイコン</a>
                <a>アイコン</a>
            </div>
        </nav>
    </footer>
</template>
