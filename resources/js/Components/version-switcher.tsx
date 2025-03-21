import * as React from "react";
import { Check, ChevronsUpDown, GalleryVerticalEnd } from "lucide-react";
import { IoPersonOutline } from "react-icons/io5";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import {
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from "@/Components/ui/sidebar";
import { Inertia } from "@inertiajs/inertia";

export function VersionSwitcher({
    versions,
    defaultVersion,
}: {
    versions: string[];
    defaultVersion: string;
}) {
    const [selectedVersion, setSelectedVersion] =
        React.useState(defaultVersion);

    return (
        <SidebarMenu>
            <SidebarMenuItem>
                <SidebarMenuButton
                    size="lg"
                    className="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground"
                    onClick={()  => Inertia.get('/')}
                >
                    <div className="flex aspect-square size-8 items-center justify-center rounded-lg bg-primary-fg text-sidebar-primary-foreground" >
                        <IoPersonOutline />
                    </div>
                    <div className="flex flex-col gap-0.5 leading-none">
                        <span className="font-semibold">
                            Oriesy
                        </span>
                        <span className="">Universitas Diponegro</span>
                    </div>
                  
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    );
}
