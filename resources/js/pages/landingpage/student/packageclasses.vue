<script setup lang="ts">
import PackageClasses from '@/pages/landingpage/sharedUI/packageclasses.vue'

interface ClassRow {
  id: number
  label: string
  date: string | null
  status: 'done' | 'upcoming' | 'to_be_scheduled' | 'schedulable'
}

interface Package {
  id: number
  uuid: string
  total_class_count: number
  complete_class_count: number
  remaining_class_count: number
  available_date: string | null
  purchase_date: string | null
  tutor_custom_price: number
  status: 'active' | 'completed'
}

interface Subject {
  id: number
  title: string
}

interface Tutor {
  id: number
  uuid: string
  name: string
  photo: string | null
}

defineProps<{
  tutor: Tutor
  subject: Subject
  package: Package
  classes: ClassRow[]
}>()
</script>

<template>
  <PackageClasses
    role="student"
    :person="tutor"
    :subject="subject"
    :package="package"
    :classes="classes"
  />
</template>