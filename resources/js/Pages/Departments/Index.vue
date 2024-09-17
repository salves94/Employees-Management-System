<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Tc from '@/Components/TableColumn.vue';
import Table from '@/Components/Table.vue';
import AnchorLink from '@/Components/AnchorLink.vue';
import { Link } from '@inertiajs/vue3';
import Heading from '@/Components/Heading.vue';
import Pagination from '@/Components/Pagination.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    departments: {
        type: Object,
    },
    sortby: {
        type: String,
    },
    sort: {
        type: String,
    }
});

const destroy = (id) => {
    router.delete(route('departments.destroy', id), {preserveScroll: true, preserveScroll: false});
};
const employees = (id) => {
    router.get(route('employees.index', {department_id: id}));
};

const sortColumn = (col) => {
    var sort = props.sort;
    if (col == props.sortby) {
        if (props.sort == "asc") {
            sort = "desc";
        } else {
            sort = "asc";
        }
    }

router.get(route("departments.index"), {
    sortby: col,
    sort: sort,
    page: props.departments.current_page,
    });
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <Heading>Departments</Heading>
        </template>
        
        <div class="flex items-center justify-end mb-6">
            <AnchorLink :href="route('departments.create')" v-if="$page.props.can.create"> Create Department </AnchorLink>
        </div>
        <Table>
            <template #header>
                <Tc @click="sortColumn('id')"
                :sortHeader="true"
                field="id"
                :sortby="sortby"
                :sort="sort"
                >ID</Tc>

                <Tc @click="sortColumn('name')"
                :sortHeader="true"
                field="name"
                :sortby="sortby"
                :sort="sort"
                >Name</Tc>

                <Tc @click="sortColumn('email')"
                :sortHeader="true"
                field="email"
                :sortby="sortby"
                :sort="sort"
                >Email</Tc>

                <Tc>Phone</Tc>
                <Tc>Actions</Tc>
            </template>                        
            <tr v-for="d in departments.data" :key="d.id" class="hover:bg-gray-200">
                <Tc>{{d.id}}</Tc>
                <Tc>{{d.name}}</Tc>
                <Tc>{{d.email}}</Tc>
                <Tc>{{d.phone}}</Tc>
                <Tc>
                    <AnchorLink mode="edit" :href="route('departments.edit', d.id)" v-if="d.can.edit">Edit</AnchorLink>                    
                    <AnchorLink mode="delete" @click="destroy(d.id)" v-if="d.can.delete">Delete</AnchorLink>
                    <AnchorLink mode="view" @click="employees(d.id)">View</AnchorLink>
                </Tc>
            </tr>
        </Table>
        <Pagination :links="departments.links">
        </Pagination>
    </AuthenticatedLayout>
</template>
