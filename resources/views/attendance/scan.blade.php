@extends('layouts.app')

@section('styles')
<style>
    #cctv-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .cam-box {
        position: relative;
        background: #000;
        border-radius: 1.2rem;
        overflow: hidden;
        border: 2px solid var(--border);
        aspect-ratio: 16/9;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .cam-box:hover { border-color: var(--primary); }
    .cam-box video { width: 100%; height: 100%; object-fit: cover; background: #000; }
    .cam-box canvas { position: absolute; top: 0; left: 0; pointer-events: none; z-index: 10; }
    .cam-label {
        position: absolute; top: 1rem; left: 1rem; background: rgba(0,0,0,0.7); color: #fff; padding: 0.3rem 0.8rem; border-radius: 0.5rem; font-size: 0.7rem; font-weight: 700; z-index: 20; letter-spacing: 1px; backdrop-filter: blur(5px); pointer-events: none;
    }
    .close-cam {
        position: absolute; top: 1rem; right: 1rem; background: rgba(239, 68, 68, 0.9); color: white; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 30; font-weight: bold; border: none; transition: 0.2s;
    }
    .close-cam:hover { background: #ef4444; transform: scale(1.1); }
    
    /* Styling Dropdown agar solid dan tidak putih */
    #add-cam-select {
        background: #1e293b; 
        color: white; 
        border: 1px solid var(--primary); 
        padding: 0.6rem 1rem; 
        border-radius: 0.75rem; 
        font-size: 0.85rem; 
        width: 200px;
        outline: none;
        cursor: pointer;
    }
    #add-cam-select option {
        background: #1e293b;
        color: white;
        padding: 10px;
    }

    .status-panel { display: grid; grid-template-columns: 1fr 350px; gap: 1.5rem; }
    .log-box { height: 400px; overflow-y: auto; background: var(--card-bg); padding: 1.5rem; border-radius: 1.5rem; border: 1px solid var(--border); }
    .log-item { padding: 1rem; border-bottom: 1px solid var(--border); animation: slideIn 0.3s ease-out; display: flex; align-items: center; gap: 1rem; }
    
    .mode-btn.active {
        background: var(--primary) !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }
</style>
@endsection

@section('content')
<div class="glass-card" style="padding: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.5rem;">🖥️ Multi-Cam Monitoring</h2>
            <p id="status-text" style="color: var(--primary); font-weight: 800; font-size: 0.8rem;">SYSTEM ACTIVE</p>
        </div>
        <div style="display: flex; gap: 1rem; align-items: center;">
            <div id="mode-toggle" style="display: flex; background: rgba(255,255,255,0.05); padding: 0.3rem; border-radius: 0.8rem; border: 1px solid var(--border);">
                <button onclick="setMode('in')" id="btn-in" class="mode-btn active" style="padding: 0.4rem 1rem; border-radius: 0.6rem; border: none; cursor: pointer; font-weight: 600; font-size: 0.75rem; transition: 0.3s;">MASUK</button>
                <button onclick="setMode('out')" id="btn-out" class="mode-btn" style="padding: 0.4rem 1rem; border-radius: 0.6rem; border: none; background: transparent; color: var(--text-dim); cursor: pointer; font-weight: 600; font-size: 0.75rem; transition: 0.3s;">PULANG</button>
            </div>
            <select id="add-cam-select">
                <option value="">+ Tambah Kamera</option>
            </select>
            <a href="/" class="btn" style="background: rgba(255,255,255,0.05); color: #fff; font-size: 0.8rem;">← Dashboard</a>
        </div>
    </div>

    <div id="cctv-grid"></div>

    <div class="status-panel">
        <div class="glass-card" style="padding: 1.5rem; text-align: center;">
            <p id="system-msg" style="color: var(--text-dim); font-size: 0.9rem;">Pilih kamera atau biarkan sistem mendeteksi otomatis.</p>
        </div>
        <div class="log-box" id="log-list">
            <div style="color: var(--text-dim); font-size: 0.7rem; text-transform: uppercase; margin-bottom: 1rem; font-weight: 800;">Real-time Logs</div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
    const grid = document.getElementById('cctv-grid');
    const statusText = document.getElementById('status-text');
    const logList = document.getElementById('log-list');
    const addCamSelect = document.getElementById('add-cam-select');
    
    const students = @json($students);
    const studentData = {};
    students.forEach(s => {
        const age = s.birth_date ? Math.floor((new Date() - new Date(s.birth_date)) / 31557600000) : '?';
        studentData[s.student_id] = { name: s.name, age };
    });

    let currentMode = 'in';
    let faceMatcher;
    let activeIds = new Set();
    let lastLog = {};

    function setMode(mode) {
        currentMode = mode;
        document.getElementById('btn-in').classList.toggle('active', mode === 'in');
        document.getElementById('btn-out').classList.toggle('active', mode === 'out');
        
        if (mode === 'out') {
            document.getElementById('btn-in').style.background = 'transparent';
            document.getElementById('btn-in').style.color = 'var(--text-dim)';
        } else {
            document.getElementById('btn-out').style.background = 'transparent';
            document.getElementById('btn-out').style.color = 'var(--text-dim)';
        }
    }

    async function init() {
        try {
            statusText.innerText = "INITIALIZING AI...";
            const URL = "/models";
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(URL),
                faceapi.nets.faceLandmark68Net.loadFromUri(URL),
                faceapi.nets.faceRecognitionNet.loadFromUri(URL),
                faceapi.nets.faceExpressionNet.loadFromUri(URL),
                faceapi.nets.ageGenderNet.loadFromUri(URL)
            ]);

            const labeled = students.map(s => {
                if(!s.face_descriptor) return null;
                return new faceapi.LabeledFaceDescriptors(s.student_id, [new Float32Array(Object.values(JSON.parse(s.face_descriptor)))]);
            }).filter(d => d !== null);

            faceMatcher = labeled.length ? new faceapi.FaceMatcher(labeled, 0.5) : null;
            statusText.innerText = "AI ONLINE";

            refreshCamList();

            addCamSelect.onchange = () => {
                const id = addCamSelect.value;
                if (id) setupCamera(id, addCamSelect.options[addCamSelect.selectedIndex].text);
                addCamSelect.value = "";
            };

        } catch (e) { statusText.innerText = "CRITICAL ERROR: " + e.message; }
    }

    async function refreshCamList() {
        const devices = await navigator.mediaDevices.enumerateDevices();
        const video = devices.filter(d => d.kind === 'videoinput');
        
        let html = '<option value="">+ Tambah Kamera</option>';
        video.forEach((d, i) => {
            if (!activeIds.has(d.deviceId)) html += `<option value="${d.deviceId}">${d.label || 'Kamera '+ (i+1)}</option>`;
        });
        addCamSelect.innerHTML = html;

        if (activeIds.size === 0 && video.length > 0) setupCamera(video[0].deviceId, video[0].label || 'CAM-1');
    }

    async function setupCamera(id, label) {
        if (activeIds.has(id)) return;
        activeIds.add(id);
        refreshCamList();

        const box = document.createElement('div');
        box.className = 'cam-box';
        box.id = `box-${id}`;
        box.innerHTML = `
            <div class="cam-label">${label}</div>
            <button class="close-cam" onclick="closeCamera('${id}')">✕</button>
            <video id="vid-${id}" autoplay muted playsinline></video>
        `;
        grid.appendChild(box);

        const videoEl = box.querySelector('video');
        try {
            console.log("Requesting camera access for ID:", id);
            const constraints = { 
                video: { 
                    deviceId: id ? { ideal: id } : undefined,
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                } 
            };
            const stream = await navigator.mediaDevices.getUserMedia(constraints);
            videoEl.srcObject = stream;
            videoEl.currentStream = stream;
            console.log("Camera access granted.");
            
            videoEl.onplay = () => {
                const canvas = faceapi.createCanvasFromMedia(videoEl);
                box.appendChild(canvas);
                const size = { width: videoEl.offsetWidth, height: videoEl.offsetHeight };
                faceapi.matchDimensions(canvas, size);

                setInterval(async () => {
                    if (videoEl.paused || videoEl.ended) return;
                    const detections = await faceapi.detectAllFaces(videoEl, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceExpressions().withAgeAndGender().withFaceDescriptors();
                    const resized = faceapi.resizeResults(detections, size);
                    canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);

                    resized.forEach(d => {
                        const match = faceMatcher ? faceMatcher.findBestMatch(d.descriptor) : { label: 'unknown' };
                        const s = studentData[match.label];
                        const emotion = Object.keys(d.expressions).reduce((a, b) => d.expressions[a] > d.expressions[b] ? a : b);
                        const labelText = s ? `${s.name} (${s.age}y, ${emotion})` : 'Unknown';

                        new faceapi.draw.DrawBox(d.detection.box, { label: labelText, boxColor: s ? '#6366f1' : '#f87171' }).draw(canvas);
                        if (s) processAbsen(match.label, videoEl);
                    });
                }, 600);
            };
        } catch (e) {
            box.style.background = '#1e293b';
            box.innerHTML += `<p style="color:red; font-size:0.7rem; position:absolute;">FAIL: ${e.message}</p>`;
        }
    }

    function closeCamera(id) {
        const box = document.getElementById(`box-${id}`);
        if (!box) return;
        const vid = box.querySelector('video');
        if (vid && vid.currentStream) vid.currentStream.getTracks().forEach(t => t.stop());
        box.remove();
        activeIds.delete(id);
        refreshCamList();
    }

    function processAbsen(id, vid) {
        const logKey = id + '_' + currentMode;
        if (lastLog[logKey] && (Date.now() - lastLog[logKey] < 30000)) return;
        lastLog[logKey] = Date.now();

        const c = document.createElement('canvas');
        c.width = vid.videoWidth; c.height = vid.videoHeight;
        c.getContext('2d').drawImage(vid, 0, 0);
        
        fetch("{{ route('scan.store') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ 
                student_id: id, 
                photo: c.toDataURL('image/jpeg', 0.6),
                type: currentMode 
            })
        }).then(r => r.json()).then(res => {
            const item = document.createElement('div');
            item.className = 'log-item';
            const now = new Date();
            const timeStr = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0') + ':' + now.getSeconds().toString().padStart(2, '0');
            item.innerHTML = `
                <div style="flex:1">
                    <div style="display: flex; justify-content: space-between;">
                        <strong>${res.student || id}</strong>
                        <span style="font-size: 0.7rem; color: var(--text-dim);">${timeStr}</span>
                    </div>
                    <small style="color: ${res.success ? '#4ade80' : '#f87171'}">${res.message}</small>
                </div>`;
            logList.prepend(item);
        });
    }

    init();
</script>
@endsection
