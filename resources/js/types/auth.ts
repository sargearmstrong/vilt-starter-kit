export type User = {
    publicId: string;
    name: string;
    email: string;
};

export type Auth = {
    user: User | null;
    roles: string[];
    permissions: string[];
};
