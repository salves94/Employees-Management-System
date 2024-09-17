<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Tc from '@/Components/TableColumn.vue';
import Table from '@/Components/Table.vue';
import AnchorLink from '@/Components/AnchorLink.vue';
import { Link } from '@inertiajs/vue3';
import Heading from '@/Components/Heading.vue';
import Select from '@/Components/Select.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    employees: {
        type: Object,
    },
    department_id: {
        type: [String, Number],
    },
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

const sortColumn = (col) => {
    var sort = props.sort;
    if (col == props.sortby) {
        if (props.sort == "asc") {
            sort = "desc";
        } else {
            sort = "asc";
        }
    }

router.get(route("employees.index"), {
    sortby: col,
    sort: sort,
    page: props.employees.current_page,
    });
};

const destroy = (id) => {
    router.delete(route('employees.destroy', id), {preserveScroll: true, preserveScroll: false});
};

const getEmployees = (department_id) => {
    router.get(
    route("employees.index"),
    { department_id: department_id },
    { only: ["employees", "department_id"] });
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <Heading>Employees</Heading>
        </template>
        
        <div class="flex items-center justify-between mb-6">        
            <Select
                id="department_id"
                class="mt-1 block w-1/3"
                v-model="props.department_id"
                :options="departments"
                @change="getEmployees(props.department_id)"
            ></Select>
            <AnchorLink :href="route('employees.create')" v-if="$page.props.can.create"> Create Employee </AnchorLink>
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
                <Tc>Department</Tc>
                <Tc>Actions</Tc>
            </template>                        
            <tr v-for="d in employees" :key="d.id" class="hover:bg-gray-200">
                <Tc>{{d.id}}</Tc>
                <Tc>{{d.name}}</Tc>
                <Tc>{{d.email}}</Tc>
                <Tc>{{d.department}}</Tc>
                <Tc>
                    <AnchorLink mode="edit" :href="route('employees.edit', d.id)" v-if="d.can.edit">Edit</AnchorLink>                    
                    <AnchorLink mode="delete" @click="destroy(d.id)" v-if="d.can.delete">Delete</AnchorLink>
                </Tc>
            </tr>
        </Table>
    </AuthenticatedLayout>
</template>
