<template>
  <div class="py-6">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Create New Post</h2>
         <Link
                    :href="route('posts.index')"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gray-600 rounded-md shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                >
                    Back to Posts
                </Link>
      </div>

      <div class="overflow-hidden bg-white shadow sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <form @submit.prevent="submit">
            <div v-if="errors.duplicate" class="p-4 mb-4 text-red-700 bg-red-100 rounded-md">
              {{ errors.duplicate }}
            </div>

            <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
              <!-- Induction Phase -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Induction Phase</label>
                <select
                  v-model="form.induction_phase_id"
                  class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                  <option value="">Select Induction Phase</option>
                  <option
                    v-for="phase in inductionPhases"
                    :key="phase.id"
                    :value="phase.id"
                  >
                    {{ phase.title }}
                  </option>
                </select>
                <p v-if="errors.induction_phase_id" class="mt-2 text-sm text-red-600">
                  {{ errors.induction_phase_id }}
                </p>
              </div>

              <!-- Category -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Category</label>
                <select
                  v-model="form.post_category_id"
                  class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                  <option value="">Select Category</option>
                  <option
                    v-for="category in categories"
                    :key="category.id"
                    :value="category.id"
                  >
                    {{ category.name }}
                  </option>
                </select>
                <p v-if="errors.post_category_id" class="mt-2 text-sm text-red-600">
                  {{ errors.post_category_id }}
                </p>
              </div>

              <!-- Qualification Type -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Required Degree</label>
                <select
                  v-model="form.qualification_type_id"
                  class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                  <option value="">Select Qualification</option>
                  <option
                    v-for="qualification in qualificationTypes"
                    :key="qualification.id"
                    :value="qualification.id"
                  >
                    {{ qualification.name }}
                  </option>
                </select>
                <p v-if="errors.qualification_type_id" class="mt-2 text-sm text-red-600">
                  {{ errors.qualification_type_id }}
                </p>
              </div>

              <!-- Post Name -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Post Name</label>
                <input
                  v-model="form.name"
                  type="text"
                  class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
                <p v-if="errors.name" class="mt-2 text-sm text-red-600">
                  {{ errors.name }}
                </p>
              </div>

              <!-- Number of Posts -->
              <div>
                <label class="block text-sm font-medium text-gray-700">No of Posts</label>
                <input
                  v-model="form.number_post"
                  type="number"
                  min="1"
                  class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
                <p v-if="errors.number_post" class="mt-2 text-sm text-red-600">
                  {{ errors.number_post }}
                </p>
              </div>

            <!--Fee field-->
 <div>
                <label class="block text-sm font-medium text-gray-700">Post Fee</label>
                <input
                  v-model="form.fee_post"
                  type="number"
                  min="1"
                  class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
                <p v-if="errors.fee_post" class="mt-2 text-sm text-red-600">
                  {{ errors.fee_post }}
                </p>
              </div>
              <!-- Age Range -->
              <div class="sm:col-span-2">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Min. Age</label>
                    <input
                      v-model="form.min_age"
                      type="number"
                      min="18"
                      max="60"
                      class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    <p v-if="errors.min_age" class="mt-2 text-sm text-red-600">
                      {{ errors.min_age }}
                    </p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Max. Age</label>
                    <input
                      v-model="form.max_age"
                      type="number"
                      min="18"
                      max="60"
                      class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    <p v-if="errors.max_age" class="mt-2 text-sm text-red-600">
                      {{ errors.max_age }}
                    </p>
                  </div>
                </div>
              </div>

              <!-- Abbreviation -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Abbreviation</label>
                <input
                  v-model="form.post_abbreviation"
                  type="text"
                  maxlength="10"
                  class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
                <p v-if="errors.post_abbreviation" class="mt-2 text-sm text-red-600">
                  {{ errors.post_abbreviation }}
                </p>
              </div>

              <!-- Gender -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Gender</label>
                <select
                  v-model="form.post_gender"
                  class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                  <option value="Both">Both</option>
                </select>
                <p v-if="errors.post_gender" class="mt-2 text-sm text-red-600">
                  {{ errors.post_gender }}
                </p>
              </div>

              <!-- BPS -->
              <div>
                <label class="block text-sm font-medium text-gray-700">BPS</label>
              
                <select
                  v-model="form.bps"
               
                  class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                  <option value="">Select BPS</option>
                  <option
                    v-for="level in postLevels"
                    :key="level.id"
                    :value="level.id"
                  >
                   {{ 'BPS-' }} {{ level.name }}
                  </option>
                </select>
            
                <p v-if="errors.bps" class="mt-2 text-sm text-red-600">
                  {{ errors.bps }}
                </p>
              </div>

              <!-- Degree Required -->
              <div class="flex items-center">
                <input
                  id="degree_required"
                  v-model="form.degree_required"
                  type="checkbox"
                  class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                />
                <label for="degree_required" class="block ml-2 text-sm text-gray-700">
                  Degree Required
                </label>
              </div>

              <!-- Requirements -->
              <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Requirements</label>
                <textarea
                  v-model="form.requirements"
                  rows="4"
                  class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                ></textarea>
                <p v-if="errors.requirements" class="mt-2 text-sm text-red-600">
                  {{ errors.requirements }}
                </p>
              </div>
            </div>

            <div class="flex justify-end mt-6">
              <button
                type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-[#0e5723] rounded-md shadow-sm hover:bg-[#1B2850] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0e5723]"
                :disabled="processing"
              >
                <span v-if="processing">Processing...</span>
                <span v-else>Create Post</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

defineOptions({
  layout: AuthenticatedLayout,
});

const props = defineProps({
  inductionPhases: Array,
  postLevels: Array,
  categories: Array,
  qualificationTypes: Array,
  errors: Object,
});
const form = useForm({
  induction_phase_id: "",
  post_category_id: "",
  qualification_type_id: "",
  name: "",
  number_post: "",
  fee_post: "",
  min_age: "",
  max_age: "",
  post_abbreviation: "",
  post_gender: "Both",
  bps: "",
  requirements: "",
  degree_required: false,
});

const processing = ref(false);

const submit = () => {
  processing.value = true;
  form.post(route("posts.store"), {
    preserveScroll: true,
    onFinish: () => {
      processing.value = false;
    },
  });
};
</script>
