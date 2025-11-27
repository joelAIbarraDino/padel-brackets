<script setup lang="ts">
// import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Medal, Trophy, Group, Users, User, Sparkles, Badge, House, Laptop, CalendarDays, ShieldUser } from 'lucide-vue-next';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder} from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { dashboard, player } from '@/routes';

const page = usePage();

const isAdmin = computed(() => {
    const user = page.props.auth?.user;
    return user && user.roles && user.roles.length > 0
        ? user.roles[0].name === 'admin'
        : false;
});

const mainNavItemsAdmin = [
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
        title: "Membresías",
        url: "#",
        icon: Sparkles,
        isActive: true,
        items: [
            {
                title: "Membresías",
                url: "/memberships",
                icon: Badge
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
                title: "Supervisores",
                url: "/supervisor",
                icon: ShieldUser
            }
        ]
    },
];

const mainNavItemsPlayer = [
    {
        title: "Home",
        url: "#",
        icon: Laptop,
        isActive: true,
        items: [
            {
                title: "Pagina principal",
                url: "/",
                icon: House
            },
            {
                title: 'Ver mas torneos',
                url: '/torneos',
                icon: CalendarDays
            },
        ]
    }
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
                        <Link v-if="isAdmin" :href="dashboard()">
                            <AppLogo />
                        </Link>

                        <Link v-else :href="player()">
                            <AppLogo />
                        </Link>

                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain v-if="isAdmin" :items="mainNavItemsAdmin" />
            <NavMain v-else :items="mainNavItemsPlayer" />
        </SidebarContent>

        <SidebarFooter>
            <!-- <NavFooter :items="footerNavItems" /> -->
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
