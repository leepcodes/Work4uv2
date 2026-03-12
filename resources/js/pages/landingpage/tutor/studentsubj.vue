<script setup lang="ts">
import SubjectPanel from '@/pages/landingpage/sharedUI/subjectpanel.vue'

interface Package {
  id: number
  uuid: string
  package_number: number
  total_class_count: number
  complete_class_count: number
  remaining_class_count: number
  available_date: string | null
  purchase_date: string | null
  tutor_custom_price: number
  status: 'active' | 'completed'
}

interface SubjectWithPackages {
  id: number
  title: string
  packages: Package[]
}

interface Student {
  id: number
  uuid: string
  name: string
  photo: string | null
}

defineProps<{
  student: Student
  subjects: SubjectWithPackages[]
}>()
</script>

<template>
  <SubjectPanel
    role="tutor"
    :person="student"
    :subjects="subjects"
    backPath="/tutor/mystudents"
    backLabel="Students List"
    breadcrumbLabel="Student & Subjects"
  />
</template>