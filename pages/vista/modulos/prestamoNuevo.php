<div class="container-xxl flex-grow-2 container-p-y">

    <div class="d-flex align-items-center justify-content-between py-3">
        <h4 class="fw-bold py-1 m-0"><span class="text-muted fw-light">Préstamos /</span> Nuevo préstamo</h4>
    </div>
    <div class="card">
        <form class="m-4">
            <div class="mb-3">
                <label class="form-label" for="basic-default-fullname">Full Name</label>
                <input type="text" class="form-control" id="basic-default-fullname" placeholder="John Doe" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="basic-default-company">Company</label>
                <input type="text" class="form-control" id="basic-default-company" placeholder="ACME Inc." />
            </div>
            <div class="mb-3">
                <label class="form-label" for="basic-default-email">Email</label>
                <div class="input-group input-group-merge">
                <input
                    type="text"
                    id="basic-default-email"
                    class="form-control"
                    placeholder="john.doe"
                    aria-label="john.doe"
                    aria-describedby="basic-default-email2"
                />
                <span class="input-group-text" id="basic-default-email2">@example.com</span>
                </div>
                <div class="form-text">You can use letters, numbers & periods</div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="basic-default-phone">Phone No</label>
                <input
                type="text"
                id="basic-default-phone"
                class="form-control phone-mask"
                placeholder="658 799 8941"
                />
            </div>
            <div class="mb-3">
                <label class="form-label" for="basic-default-message">Message</label>
                <textarea
                id="basic-default-message"
                class="form-control"
                placeholder="Hi, Do you have a moment to talk Joe?"
                ></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </div>
</div>
