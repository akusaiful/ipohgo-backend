<template>
    <div
        class="d-flex flex-column h-100 "
    >
        <div class="text-center flex-shrink-0 shadow-sm">
            <h5 class="modal-title font-weight-medium text-center px-3 py-2"><i class="fa fa-edit"></i> {{ currentBlockSetting.name }}</h5>
        </div>
        <div
            class="flex-grow-1 overflow-auto p-3"
        >
            <vue-form-generator
                :schema="{fields:schema}"
                :model="model"
                :options="options"
            ></vue-form-generator>
        </div>
        <div class="modal-footer flex-shrink-0 p-2">
            <button
                type="button"
                class="btn btn-secondary"
                @click="hideModal"
            >{{ template_i18n.cancel }}
            </button>
            <button
                type="button"
                class="btn btn-primary"
                @click="saveModal"
            >{{ template_i18n.save_block }}
                <i
                    class="fa fa-spin fa-spinner"
                    v-show="onSaving"
                ></i>
            </button>
        </div>
    </div>

</template>
<script>
import VueFormGenerator from "vue-form-generator";

export default {
    data: function () {
        return {
            item: {},
            block: {},
            model: {},
            onEdit: false,
            template_i18n: template_i18n,
            options: {},
            tmp_block: {},
        }
    },
    props: {
        currentModel: {},
        currentBlockSetting: {},
        id: '',
        onSaving:false
    },
    computed: {
        schema() {
            return this.currentBlockSetting.settings ?? {}
        }
    },
    watch: {
        currentModel(val) {
            this.model = Object.assign({}, val ?? {});
        }
    },
    components: {
        "vue-form-generator": VueFormGenerator.component,
    },
    methods: {
        saveModal() {
            this.$emit('save', Object.assign({}, this.model))
        },
        hideModal() {
            this.$emit('cancel')
        }
    },
    mounted() {
        this.model = Object.assign({}, this.currentModel ?? {});
    }
}
</script>
