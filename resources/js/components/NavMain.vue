<script setup lang="ts">
import type { LucideIcon } from "lucide-vue-next"
import { ChevronRight, LayoutDashboard } from "lucide-vue-next"
import { urlIsActive } from '@/lib/utils';
import {
  Collapsible,
  CollapsibleContent,
  CollapsibleTrigger,
} from "@/components/ui/collapsible"
import {
  SidebarGroup,
  SidebarGroupLabel,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
  SidebarMenuSub,
  SidebarMenuSubButton,
  SidebarMenuSubItem,
} from "@/components/ui/sidebar"
import { Link, usePage } from "@inertiajs/vue3";
import { dashboard } from "@/routes";

defineProps<{
  items: {
    title: string
    url: string
    icon?: LucideIcon
    isActive?: boolean
    items?: {
      title: string
      url: string
      icon?: LucideIcon
    }[]
  }[]
}>()
const page = usePage();
</script>

<template>
    <SidebarGroup>
        <SidebarGroupLabel>Menú</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem>
                <SidebarMenuButton as-child :is-active="urlIsActive(dashboard(), page.url)">
                    <Link :href="dashboard()"><LayoutDashboard /> Dashboard</Link>
                </SidebarMenuButton>
            </SidebarMenuItem>

            <Collapsible
                v-for="item in items"
                :key="item.title"
                as-child
                :default-open="item.isActive"
                class="group/collapsible"
            >
                <SidebarMenuItem>
                    <CollapsibleTrigger as-child>
                        <SidebarMenuButton :tooltip="item.title">
                        <component :is="item.icon" v-if="item.icon" />
                        <span>{{ item.title }}</span>
                        <ChevronRight class="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90" />
                        </SidebarMenuButton>
                    </CollapsibleTrigger>
                    <CollapsibleContent>
                        <SidebarMenuSub>
                        <SidebarMenuSubItem v-for="subItem in item.items" :key="subItem.title">
                            <SidebarMenuSubButton as-child :is-active="urlIsActive(subItem.url, page.url)">
                            <Link :href="subItem.url" class="flex justify-between items-center">
                              <span>{{ subItem.title }}</span>
                              <component :is="subItem.icon" />
                            </Link>
                            </SidebarMenuSubButton>
                        </SidebarMenuSubItem>
                        </SidebarMenuSub>
                </CollapsibleContent>
                </SidebarMenuItem>
            </Collapsible>
        </SidebarMenu>
    </SidebarGroup>
</template>
