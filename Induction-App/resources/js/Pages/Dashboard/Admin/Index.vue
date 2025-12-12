<template>
<div class="min-h-screen">
        <PageHeader title="Admin Dashboard" subtitle="Overview and quick actions" />
            <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-2 lg:grid-cols-5">
                <Card label="Total Registered" value="20,000"  icon="users" trendPositive />
                <Card label="Approved" value="15,000"  icon="subscription"
                    trendPositive />
                <Card label="Pending" value="5,000"  icon="free-users"
                    trendPositive />
                <Card label="Total Income" value="42,000"  icon="income"
                    trendPositive />
                <Card label="Total Due" value="30,000"  icon="expense" />
            </div>

        <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
            <!-- Candidate Statistics Chart (Appendix 1) -->
            <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Current Phase Candidate Statistic</h3>
                </div>
                <CandidateChart />
            </div>

            <!-- Users Overview Chart (Appendix 2) -->
            <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Overall User Overview</h3>
                </div>
                <UserOverviewChart />
            </div>
        </div>

        <!-- <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Latest Registered</h3>
                    <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full">35</span>
                </div>
                <LatestRegistered />
            </div>

            <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Top Performer</h3>
                    <a href="#" class="text-sm font-medium text-purple-600 hover:text-purple-800">View All</a>
                </div>
                <TopPerformer />
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
            <a :href="route('admin.support.index')" class="p-5 bg-white rounded-xl shadow border hover:bg-teal-50">
                <div class="font-semibold text-gray-800 mb-1">Support Tickets</div>
                <div class="text-sm text-gray-600">View and respond to applicant queries</div>
            </a>
            <a :href="route('admin.payments.index')" class="p-5 bg-white rounded-xl shadow border hover:bg-teal-50">
                <div class="font-semibold text-gray-800 mb-1">Payments</div>
                <div class="text-sm text-gray-600">Review application fee payments</div>
            </a>
            <a :href="route('posts.index')" class="p-5 bg-white rounded-xl shadow border hover:bg-teal-50">
                <div class="font-semibold text-gray-800 mb-1">Manage Posts</div>
                <div class="text-sm text-gray-600">Create and update posts and fees</div>
            </a>
        </div>
    </div>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Card from "@/Components/Dashboard/Card.vue";
import LatestRegistered from "@/Components/Dashboard/LatestRegistered.vue";
import TopPerformer from "@/Components/Dashboard/TopPerformer.vue";
import CandidateChart from "@/Components/Dashboard/CandidateChart.vue";
import UserOverviewChart from "@/Components/Dashboard/UserOverviewChart.vue";
import PageHeader from "@/Components/UI/PageHeader.vue";

defineOptions({
    layout: AuthenticatedLayout,
});
</script>
<style>
.text-green-600 {
    color: #0e5723 !important;
}
</style>
