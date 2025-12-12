<template>
  <aside
    class="w-64 h-screen overflow-y-auto bg-white border-r border-gray-200 shadow-sm"
  >
    <nav class="p-4">
      <ul class="space-y-1">
        <li>
          <Link
            :href="route('admin.dashboard')"
            class="flex items-center p-3 text-base font-medium text-purple-700 transition-colors rounded-lg bg-purple-50 hover:bg-purple-100"
          >
            <svg
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
              ></path>
            </svg>
            <span class="ml-3">Dashboard</span>
          </Link>
        </li>

        <!-- Regions -->
        <li
          class="relative"
          @mouseenter="openDropdown = 'regions'"
          @mouseleave="openDropdown = null"
        >
          <button
            class="flex items-center justify-between w-full p-3 text-base font-medium text-gray-700 transition-colors rounded-lg hover:bg-gray-100"
          >
            <div class="flex items-center">
              <svg
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"
                ></path>
              </svg>
              <span class="ml-3">Regions</span>
            </div>
            <svg
              class="w-4 h-4 transition-transform duration-200"
              :class="{ 'transform rotate-180': openDropdown === 'regions' }"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 9l-7 7-7-7"
              ></path>
            </svg>
          </button>
          <transition name="slide">
            <ul
              v-show="openDropdown === 'regions'"
              class="mt-1 space-y-1 pl-11"
            >
              <li>
                <Link
                  :href="route('provinces.index')"
                  class="flex items-center p-2 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-100"
                >
                  <svg
                    class="w-4 h-4 mr-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                    ></path>
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                    ></path>
                  </svg>
                  Province
                </Link>
              </li>
              <li>
                <Link
                  :href="route('districts.index')"
                  class="flex items-center p-2 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-100"
                >
                  <svg
                    class="w-4 h-4 mr-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"
                    ></path>
                  </svg>
                  District
                </Link>
              </li>
              <li>
                <Link
                  :href="route('cities.index')"
                  class="flex items-center p-2 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-100"
                >
                  <svg
                    class="w-4 h-4 mr-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"
                    ></path>
                  </svg>
                  Cities
                </Link>
              </li>
            </ul>
          </transition>
        </li>

        <!-- Induction -->
        <li
          class="relative"
          @mouseenter="openDropdown = 'induction'"
          @mouseleave="openDropdown = null"
        >
          <button
            class="flex items-center justify-between w-full p-3 text-base font-medium text-gray-700 transition-colors rounded-lg hover:bg-gray-100"
          >
            <div class="flex items-center">
              <svg
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                ></path>
              </svg>
              <span class="ml-3">Induction</span>
            </div>
            <svg
              class="w-4 h-4 transition-transform duration-200"
              :class="{ 'transform rotate-180': openDropdown === 'induction' }"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 9l-7 7-7-7"
              ></path>
            </svg>
          </button>
          <transition name="slide">
            <ul
              v-show="openDropdown === 'induction'"
              class="mt-1 space-y-1 pl-11"
            >
              <li>
                <Link
                  :href="route('InductionPhase.index')"
                  class="flex items-center p-2 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-100"
                >
                  <svg
                    class="w-4 h-4 mr-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M13 10V3L4 14h7v7l9-11h-7z"
                    ></path>
                  </svg>
                  Induction Title
                </Link>
              </li>
            </ul>
          </transition>
        </li>

        <!-- Qualification -->
        <li
          class="relative"
          @mouseenter="openDropdown = 'qualification'"
          @mouseleave="openDropdown = null"
        >
          <button
            class="flex items-center justify-between w-full p-3 text-base font-medium text-gray-700 transition-colors rounded-lg hover:bg-gray-100"
          >
            <div class="flex items-center">
              <svg
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                ></path>
              </svg>
              <span class="ml-3">Qualification</span>
            </div>
            <svg
              class="w-4 h-4 transition-transform duration-200"
              :class="{
                'transform rotate-180': openDropdown === 'qualification',
              }"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 9l-7 7-7-7"
              ></path>
            </svg>
          </button>
          <transition name="slide">
            <ul
              v-show="openDropdown === 'qualification'"
              class="mt-1 space-y-1 pl-11"
            >
              <li>
                <Link
                  :href="route('qualification-types.index')"
                  class="flex items-center p-2 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-100"
                >
                  <svg
                    class="w-4 h-4 mr-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"
                    ></path>
                  </svg>
                  Types
                </Link>
              </li>

              <li>
                <Link
                  :href="route('qualification-categories.index')"
                  class="flex items-center p-2 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-100"
                >
                  <svg
                    class="w-4 h-4 mr-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"
                    ></path>
                  </svg>
                  Categories
                </Link>
              </li>
              <!-- <li>
                <a
                  href="#"
                  class="flex items-center p-2 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-100"
                >
                  <svg
                    class="w-4 h-4 mr-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                    ></path>
                  </svg>
                  Qualification
                </a>
              </li> -->
            </ul>
          </transition>
        </li>

        <!-- Posts -->
        <li
          class="relative"
          @mouseenter="openDropdown = 'posts'"
          @mouseleave="openDropdown = null"
        >
          <button
            class="flex items-center justify-between w-full p-3 text-base font-medium text-gray-700 transition-colors rounded-lg hover:bg-gray-100"
          >
            <div class="flex items-center">
              <svg
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"
                ></path>
              </svg>
              <span class="ml-3">Posts</span>
            </div>
            <svg
              class="w-4 h-4 transition-transform duration-200"
              :class="{ 'transform rotate-180': openDropdown === 'posts' }"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 9l-7 7-7-7"
              ></path>
            </svg>
          </button>
          <transition name="slide">
            <ul v-show="openDropdown === 'posts'" class="mt-1 space-y-1 pl-11">
              <li>
                <Link
                  :href="route('PostCategory.index')"
                  class="flex items-center p-2 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-100"
                >
                  <svg
                    class="w-4 h-4 mr-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"
                    ></path>
                  </svg>
                  Post Category
                </Link>
              </li>
              <li>
                <Link
                  :href="route('posts.index')"
                  class="flex items-center p-2 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-100"
                >
                  <svg
                    class="w-4 h-4 mr-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"
                    ></path>
                  </svg>
                  Post
                </Link>
              </li>
              <li>
                <Link
                  :href="route('post_levels.index')"
                  class="flex items-center p-2 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-100"
                >
                  <svg
                    class="w-4 h-4 mr-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"
                    ></path>
                  </svg>
                  Post Level
                </Link>
              </li>
            </ul>
          </transition>
        </li>
        <li
          class="relative"
          @mouseenter="quotaSettingDropdownOpen = true"
          @mouseleave="quotaSettingDropdownOpen = false"
        >
          <button
            class="flex items-center justify-between w-full p-3 text-base font-medium text-gray-700 transition-colors rounded-lg hover:bg-gray-100"
          >
            <div class="flex items-center">
              <svg
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"
                ></path>
              </svg>
              <span class="ml-3">QuotaSetting</span>
            </div>
            <svg
              class="w-4 h-4 transition-transform duration-200"
              :class="{ 'transform rotate-180': quotaSettingDropdownOpen }"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 9l-7 7-7-7"
              ></path>
            </svg>
          </button>
          <transition name="slide">
            <ul v-show="quotaSettingDropdownOpen" class="mt-1 space-y-1 pl-11">
              <li>
                <Link
                  :href="route('QuotaSetting.index')"
                  class="flex items-center p-2 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-100"
                >
                  <svg
                    class="w-4 h-4 mr-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"
                    ></path>
                  </svg>
                  General
                </Link>
              </li>
            </ul>
          </transition>
        </li>

        <!-- Exam Center -->
        <li
          class="relative"
          @mouseenter="openDropdown = 'examCenter'"
          @mouseleave="openDropdown = null"
        >
          <button
            class="flex items-center justify-between w-full p-3 text-base font-medium text-gray-700 transition-colors rounded-lg hover:bg-gray-100"
          >
            <div class="flex items-center">
              <svg
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 26"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"
                ></path>
              </svg>
              <span class="ml-3">Exam Center</span>
            </div>
            <svg
              class="w-4 h-4 transition-transform duration-200"
              :class="{ 'transform rotate-180': openDropdown === 'examCenter' }"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 9l-7 7-7-7"
              ></path>
            </svg>
          </button>
          <transition name="slide">
            <ul
              v-show="openDropdown === 'examCenter'"
              class="mt-1 space-y-1 pl-11"
            >

             <Link
                :href="route('post.allocate')"
                class="flex items-center p-2 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-100"
              >
                <svg
                  class="w-4 h-4 mr-3"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                  ></path>
                </svg>
                Post Assign To Centers
              </Link>

              <Link
                :href="route('Centers.index')"
                class="flex items-center p-2 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-100"
              >
                <svg
                  class="w-4 h-4 mr-3"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                  ></path>
                </svg>
                Center
              </Link>


              <li>
                <Link
                  :href="route('center-allotments.index')"
                  class="flex items-center p-2 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-100"
                >
                  <svg
                    class="w-4 h-4 mr-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                    ></path>
                  </svg>
                  Center Allotment
                </Link>
              </li>

              <li>
                <Link
                  :href="route('center-posts.index')"
                  class="flex items-center p-2 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-100"
                >
                  <svg
                    class="w-4 h-4 mr-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                    ></path>
                  </svg>
                  Center Wise Post Assignment
                </Link>
              </li>

              <li>
                <Link
                  :href="route('reports.index')"
                  class="flex items-center p-2 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-100"
                >
                  <svg
                    class="w-4 h-4 mr-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    ></path>
                  </svg>
                  All Reports
                </Link>
              </li>
            </ul>
          </transition>
        </li>

        <!-- Candidate -->
        <li
          class="relative"
          @mouseenter="openDropdown = 'candidate'"
          @mouseleave="openDropdown = null"
        >
          <button
            class="flex items-center justify-between w-full p-3 text-base font-medium text-gray-700 transition-colors rounded-lg hover:bg-gray-100"
          >
            <div class="flex items-center">
              <svg
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                ></path>
              </svg>
              <span class="ml-3">Applications</span>
            </div>
            <svg
              class="w-4 h-4 transition-transform duration-200"
              :class="{ 'transform rotate-180': openDropdown === 'candidate' }"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 9l-7 7-7-7"
              ></path>
            </svg>
          </button>
          <transition name="slide">
            <ul
              v-show="openDropdown === 'candidate'"
              class="mt-1 space-y-1 pl-11"
            >
              <li>
                <Link
                  :href="route('allApplications.Index')"
                  class="flex items-center p-2 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-100"
                >
                  <svg
                    class="w-4 h-4 mr-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                    ></path>
                  </svg>
                  All Applications
                </Link>
              </li>
              <li>
                <a
                  href="#"
                  class="flex items-center p-2 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-100"
                >
                  <svg
                    class="w-4 h-4 mr-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                    ></path>
                  </svg>
                  Approved Applications
                </a>
              </li>
            </ul>
          </transition>
        </li>

        <!-- Fees -->
        <li
          class="relative"
          @mouseenter="openDropdown = 'fees'"
          @mouseleave="openDropdown = null"
        >
          <button
            class="flex items-center justify-between w-full p-3 text-base font-medium text-gray-700 transition-colors rounded-lg hover:bg-gray-100"
          >
            <div class="flex items-center">
              <svg
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                ></path>
              </svg>
              <span class="ml-3">Fees</span>
            </div>
            <svg
              class="w-4 h-4 transition-transform duration-200"
              :class="{ 'transform rotate-180': openDropdown === 'fees' }"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 9l-7 7-7-7"
              ></path>
            </svg>
          </button>
          <transition name="slide">
            <ul v-show="openDropdown === 'fees'" class="mt-1 space-y-1 pl-11">
              <li>
                <a
                  href="#"
                  class="flex items-center p-2 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-100"
                >
                  <svg
                    class="w-4 h-4 mr-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    ></path>
                  </svg>
                  Total Fee
                </a>
              </li>
              <li>
                <a
                  href="#"
                  class="flex items-center p-2 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-100"
                >
                  <svg
                    class="w-4 h-4 mr-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                    ></path>
                  </svg>
                  Pending Fee
                </a>
              </li>
            </ul>
          </transition>
        </li>

        <!-- Message -->
        <li>
          <a
            href="#"
            class="flex items-center p-3 text-base font-medium text-gray-700 transition-colors rounded-lg hover:bg-gray-100"
          >
            <svg
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"
              ></path>
            </svg>
            <span class="ml-3">Message</span>
          </a>
        </li>

        <!-- Help Desk -->
        <li>
          <a
            href="#"
            class="flex items-center p-3 text-base font-medium text-gray-700 transition-colors rounded-lg hover:bg-gray-100"
          >
            <svg
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"
              ></path>
            </svg>
            <span class="ml-3">Help Desk</span>
          </a>
        </li>

        <li
          class="relative"
          @mouseenter="openDropdown = 'setting'"
          @mouseleave="openDropdown = null"
        >
          <button
            class="flex items-center justify-between w-full p-3 text-base font-medium text-gray-700 transition-colors rounded-lg hover:bg-gray-100"
          >
            <div class="flex items-center">
              <svg
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                ></path>
              </svg>
              <span class="ml-3">Setting</span>
            </div>
            <svg
              class="w-4 h-4 transition-transform duration-200"
              :class="{ 'transform rotate-180': openDropdown === 'setting' }"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 9l-7 7-7-7"
              ></path>
            </svg>
          </button>
          <transition name="slide">
            <ul
              v-show="openDropdown === 'setting'"
              class="mt-1 space-y-1 pl-11"
            >
              <li>
                <Link
                  :href="route('Permissions.index')"
                  class="flex items-center p-2 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-100"
                >
                  <svg
                    class="w-4 h-4 mr-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                    ></path>
                  </svg>
                  Permission
                </Link>
              </li>
              <li>
                <Link
                  :href="route('Roles.index')"
                  class="flex items-center p-2 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-100"
                >
                  <svg
                    class="w-4 h-4 mr-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                    ></path>
                  </svg>
                  Roles
                </Link>
              </li>
            </ul>
          </transition>
        </li>
      </ul>
    </nav>
  </aside>
</template>

<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
const openDropdown = ref(null);
const postsDropdownOpen = ref(false);
const quotaSettingDropdownOpen = ref(false);
</script>

