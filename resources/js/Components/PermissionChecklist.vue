<template>
    <div>
        <div
            v-for="(items, group) in groupedPermissions"
            :key="group"
            class="card border-0 shadow-sm mb-3"
        >
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <strong class="text-capitalize">{{ formatGroup(group) }}</strong>
                <button
                    type="button"
                    class="btn btn-sm btn-outline-primary"
                    @click="toggleGroup(items)"
                >
                    Toggle
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div
                        v-for="permission in items"
                        :key="permission.name"
                        class="col-md-4 col-sm-6 mb-2"
                    >
                        <label class="form-check d-flex align-items-start gap-2">
                            <input
                                class="form-check-input mt-1"
                                type="checkbox"
                                :value="permission.name"
                                :checked="modelValue.includes(permission.name)"
                                @change="togglePermission(permission.name)"
                            >
                            <span class="form-check-label">{{ permission.name }}</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        permissions: {
            type: Array,
            default: () => [],
        },
        modelValue: {
            type: Array,
            default: () => [],
        },
    },

    emits: ['update:modelValue'],

    computed: {
        groupedPermissions() {
            return this.permissions.reduce((groups, permission) => {
                const group = permission.name.includes('.') ? permission.name.split('.')[0] : 'lainnya';

                if (!groups[group]) {
                    groups[group] = [];
                }

                groups[group].push(permission);
                return groups;
            }, {});
        },
    },

    methods: {
        togglePermission(name) {
            const selected = [...this.modelValue];
            const index = selected.indexOf(name);

            if (index >= 0) {
                selected.splice(index, 1);
            } else {
                selected.push(name);
            }

            this.$emit('update:modelValue', selected);
        },
        toggleGroup(items) {
            const selected = [...this.modelValue];
            const names = items.map((item) => item.name);
            const allSelected = names.every((name) => selected.includes(name));

            const nextValue = allSelected
                ? selected.filter((name) => !names.includes(name))
                : [...new Set([...selected, ...names])];

            this.$emit('update:modelValue', nextValue);
        },
        formatGroup(group) {
            return group.replace(/[-_.]/g, ' ');
        },
    },
};
</script>
