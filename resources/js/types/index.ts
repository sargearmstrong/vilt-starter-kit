export * from './auth';

import type { Auth } from './auth';

export type SharedApp = {
    name: string;
    assetVersion: string | null;
    environment: string;
};

export type Flash = {
    success?: string | null;
    error?: string | null;
};

export type SharedPageProps = {
    app: SharedApp;
    auth: Auth;
    flash: Flash;
};
