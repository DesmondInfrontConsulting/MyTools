<div class="sidebar bg-white p-4 border-end shadow-sm vh-100" style="width: 250px;">
    <h5 class="mb-4 text-primary d-flex align-items-center gap-2">
        <i class="bi bi-tools"></i> Desmond's Tools
    </h5>

    <ul class="nav flex-column">
        <li class="nav-item mb-2">
            <a href="{{ route("mykad") }}"
                class="nav-link d-flex align-items-center gap-2 fw-medium hover-link {{ request()->routeIs("mykad") ? "active text-primary" : "text-dark" }}">
                <i class="bi bi-person-badge"></i> Generate MyKad
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="{{ route("passport") }}"
                class="nav-link d-flex align-items-center gap-2 fw-medium hover-link {{ request()->routeIs("passport") ? "active text-primary" : "text-dark" }}">
                <i class="bi bi-person-badge"></i> Generate Passport
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="{{ route("mrz") }}"
                class="nav-link d-flex align-items-center gap-2 fw-medium hover-link {{ request()->routeIs("mrz") ? "active text-primary" : "text-dark" }}">
                <i class="bi bi-person-badge"></i> MRZ Generator
            </a>
        </li>
    </ul>
</div>
