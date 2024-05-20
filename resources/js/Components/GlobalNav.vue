<script setup lang="ts">
  import ApplicationLogo from "@/Components/ApplicationLogo.vue";
  import { Link } from "@inertiajs/vue3";
  import NavLink from "@/Components/NavLink.vue";
  import { ref } from "vue";
  import Dropdown from "@/Components/Dropdown.vue";
  import DropdownLink from "@/Components/DropdownLink.vue";

  const showingNavigationDropdown = ref(false);
</script>
<template>
  <!-- Primary Navigation Menu -->
  <div class="max-w-7xl mx-auto px-4">
      <div class="flex justify-between h-16">
          <div class="flex">
              <!-- Logo -->
              <div class="shrink-0 flex items-center">
                  <Link :href="route('admin.dashboard')">
                      <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                  </Link>
              </div>

              <!-- Navigation Links -->
              <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                  <NavLink :href="route('home')" :active="route().current('home')">
                      Home
                  </NavLink>

                  <NavLink :href="route('todo.index')" :active="route().current('todo.index')">
                      Todo
                  </NavLink>
                  <NavLink :href="route('product.index')" :active="route().current('product.create')">
                      EC
                  </NavLink>
                  <NavLink :href="route('subscribe')" :active="route().current('subscribe')">
                        Subscribe
                    </NavLink>
                  <NavLink :href="route('contact.create')" :active="route().current('contact.create')">
                      Contact
                  </NavLink>
              </div>
          </div>

          <!-- Settings Dropdown -->
          <div class="hidden sm:flex sm:items-center sm:ms-6">
            <div class="dropdown dropdown-end">
              <div tabindex="0" role="button" class="btn btn-ghost rounded-btn">{{ $page.props.auth.user.name }}</div>
              <ul tabindex="0" class="menu dropdown-content z-[1] p-2 shadow bg-base-100 rounded-box w-52 mt-4">
                <li>
                  <DropdownLink 
                    :href="route('admin.profile.edit')"
                    >
                    Profile
                  </DropdownLink>
                </li> 
                <li>
                  <DropdownLink
                      :href="route('admin.logout')"
                      method="post"
                      as="button"
                      >
                      admin/Log Out
                  </DropdownLink>
                </li>
              </ul>
            </div>
          </div>
      </div>
  </div>
</template>