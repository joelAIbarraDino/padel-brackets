import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
};

export interface Role {
    id: number;
    name: string;
    guard_name: string;
    created_at: string;
    updated_at: string;
    pivot: {
        model_type: string;
        model_id: number;
        role_id: number;
    };
}

export interface User {
    id: number;
    name: string;
    phone: string;
    email: string;
    email_verified_at: string | null;
    two_factor_secret: string | null;
    two_factor_recovery_codes: string | null;
    two_factor_confirmed_at: string | null;
    created_at: string;
    updated_at: string;

    roles: Role[];
}

export type BreadcrumbItemType = BreadcrumbItem;

export interface Tournament{
    id:number,
    type:number
    type_tournament?: {
        id: number,
        name: string
    },
    scheduled_event:string,
    admission_price:number,
    places:number,
    modality:string,
    status:string,
    result:string

}

export interface Membership{
    id:number,
    name:string,
    description:string,
    months_to_expire:number,
    price:number,
}

export interface TypeTournament{
    id:number,
    name:string
}

export interface Place{
    id:number,
    id_user:number,
    id_tournament:number,
    id_payment:number,
    status:string,
    user?:{
        id:number,
        name:string
    }
}

export interface Event{
    id:number,
    title:string,
    start:string,
    url:string
}

export interface Subscription {
    id: number;
    id_user: number;
    id_membership: number;
    id_payment: number | null;
    expired_at: string | null;
    created_at: string;
    updated_at: string;

    membership?: {
        id: number;
        name: string;
        description?: string;
        months_to_expire: number;
        price: number;
    };

    payment?: {
        id: number;
        amount: number;
        currency: string;
        method: string;
        status: string;
        created_at: string;
    };
}
