<script setup>
import { computed } from 'vue';
import { History, User, Calendar } from 'lucide-vue-next';

const props = defineProps({
    auditorias: {
        type: Array,
        default: () => []
    }
});

const getActionLabel = (accion) => {
    const labels = {
        'created': 'Creado',
        'updated': 'Actualizado',
        'deleted': 'Eliminado'
    };
    return labels[accion] || accion;
};

const getActionColor = (accion) => {
    const colors = {
        'created': 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
        'updated': 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
        'deleted': 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300'
    };
    return colors[accion] || 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300';
};

const formatFieldName = (field) => {
    const fieldNames = {
        'nombre': 'Nombre',
        'categoria_id': 'Categoría',
        'departamento_id': 'Departamento',
        'ubicacion_id': 'Ubicación',
        'marca_id': 'Marca',
        'modelo_id': 'Modelo',
        'color_id': 'Color',
        'estado_id': 'Estado',
        'responsable_id': 'Responsable',
        'valor_adquisicion': 'Valor de Adquisición',
        'fecha_adquisicion': 'Fecha de Adquisición',
        'vida_util_anios': 'Vida Útil (años)',
        'valor_residual': 'Valor Residual',
        'codigo_inventario': 'Código de Inventario',
        'numero_serie': 'Número de Serie',
        'proveedor_id': 'Proveedor',
        'fuente_id': 'Fuente de Financiamiento'
    };
    return fieldNames[field] || field;
};

const getChangedFields = (auditoria) => {
    if (auditoria.accion === 'created') {
        return [];
    }
    
    if (!auditoria.valores_anteriores || !auditoria.valores_nuevos) {
        return [];
    }

    const changes = [];
    const oldValues = typeof auditoria.valores_anteriores === 'string' 
        ? JSON.parse(auditoria.valores_anteriores) 
        : auditoria.valores_anteriores;
    const newValues = typeof auditoria.valores_nuevos === 'string' 
        ? JSON.parse(auditoria.valores_nuevos) 
        : auditoria.valores_nuevos;

    for (const key in newValues) {
        if (oldValues[key] !== newValues[key] && key !== 'updated_at') {
            changes.push({
                field: formatFieldName(key),
                oldValue: oldValues[key] || 'N/A',
                newValue: newValues[key] || 'N/A'
            });
        }
    }

    return changes;
};
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-base font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <History class="w-5 h-5 text-gray-500" />
                Historial de Cambios
            </h3>
        </div>

        <div v-if="auditorias.length === 0" class="p-8 text-center text-gray-500 dark:text-gray-400">
            <History class="w-12 h-12 mx-auto mb-3 text-gray-300" />
            <p>No hay historial de cambios registrado</p>
        </div>

        <div v-else class="divide-y divide-gray-100 dark:divide-gray-700">
            <div v-for="auditoria in auditorias" :key="auditoria.id" class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span :class="['px-2.5 py-0.5 rounded-full text-xs font-medium', getActionColor(auditoria.accion)]">
                            {{ getActionLabel(auditoria.accion) }}
                        </span>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                        <Calendar class="w-3 h-3" />
                        {{ auditoria.fecha_hora }}
                    </div>
                </div>

                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300 mb-2">
                    <User class="w-4 h-4" />
                    <span class="font-medium">{{ auditoria.usuario }}</span>
                </div>

                <div v-if="getChangedFields(auditoria).length > 0" class="mt-3 space-y-2">
                    <div v-for="change in getChangedFields(auditoria)" :key="change.field" 
                         class="text-xs bg-gray-50 dark:bg-gray-700/50 p-2 rounded">
                        <div class="font-medium text-gray-700 dark:text-gray-300 mb-1">{{ change.field }}</div>
                        <div class="flex items-center gap-2">
                            <span class="text-red-600 dark:text-red-400 line-through">{{ change.oldValue }}</span>
                            <span class="text-gray-400">→</span>
                            <span class="text-green-600 dark:text-green-400 font-medium">{{ change.newValue }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
