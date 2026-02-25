export default function Badge({ status }) {
    const config = {
        wishlist:  { label: 'Wishlist',   cls: 'bg-slate-100 text-slate-600 ring-slate-200' },
        applied:   { label: 'Applied',    cls: 'bg-blue-100 text-blue-700 ring-blue-200' },
        interview: { label: 'Interview',  cls: 'bg-amber-100 text-amber-700 ring-amber-200' },
        offer:     { label: 'Offer',      cls: 'bg-green-100 text-green-700 ring-green-200' },
        rejected:  { label: 'Rejected',   cls: 'bg-red-100 text-red-600 ring-red-200' },
    };

    const item = config[status] || config.wishlist;

    return (
        <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ring-1 ${item.cls}`}>
            {item.label}
        </span>
    );
}
