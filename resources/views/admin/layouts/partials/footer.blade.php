<style>
    .app-footer {
        padding: 20px 24px;
        background-color: white;
        border-top: 1px solid #f1f1f4;
    }

    .footer-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .footer-copyright {
        color: #78829d;
        font-size: 13px;
    }

    .footer-copyright a {
        color: #071324;
        font-weight: 600;
        text-decoration: none;
    }

    .footer-copyright a:hover {
        color: #701229;
    }

    .footer-menu {
        display: flex;
        gap: 20px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .footer-menu li a {
        color: #78829d;
        font-size: 13px;
        text-decoration: none;
        transition: color 0.2s;
    }

    .footer-menu li a:hover {
        color: #071324;
    }

    @media (max-width: 768px) {
        .footer-content {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="app-footer">
    <div class="footer-content">
        <div class="footer-copyright">
            <span class="text-muted fw-semibold me-1">{{ date('Y') }} &copy;</span>
            <a href="#" target="_blank">BPKAD Provinsi Kalimantan Timur</a>
        </div>
        <ul class="footer-menu">
            <li><a href="#" target="_blank">About</a></li>
            <li><a href="#" target="_blank">Support</a></li>
            <li><a href="#" target="_blank">Purchase</a></li>
        </ul>
    </div>
</div>