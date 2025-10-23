<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Medal, Trophy, Group, Users, Sparkles } from 'lucide-vue-next';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder} from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems = [
    {
        title: "Torneos",
        url: "#",
        icon: Trophy,
        isActive: true,
        items: [
            {
                title: "Torneos registrados",
                url: "/tournaments",
                icon: Medal
            },
            {
                title: 'Tipo de torneos',
                url: '/type-tournaments',
                icon: Group
            },
        ]
    },
    {
        title: "Usuarios",
        url: "#",
        icon: Users,
        isActive: false,
        items: [
            {
                title: "Torneos registrados",
                url: "/tournaments",
                icon: Medal
            },
            {
                title: 'Tipo de torneos',
                url: '/type-tournaments',
                icon: Group
            },
        ]
    },
        {
        title: "Membresías",
        url: "#",
        icon: Sparkles,
        isActive: false,
        items: [
            {
                title: "Torneos registrados",
                url: "/tournaments",
                icon: Medal
            },
            {
                title: 'Tipo de torneos',
                url: '/type-tournaments',
                icon: Group
            },
        ]
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <!-- <NavFooter :items="footerNavItems" /> -->
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
