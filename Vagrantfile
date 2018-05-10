Vagrant.configure("2") do |config|

    #14.04 LTS
    config.vm.box = "Knowy/Ubuntu-Trusty64-Docker"

    config.vm.network "private_network", ip: "192.168.10.12"

    config.vm.hostname = "tsg.info"

    # Fix ssh collision if another vagrant is up
    config.vm.network :forwarded_port, guest: 22, host: 2208, id: "ssh", auto_correct: true

    config.vm.provider :virtualbox do |vb|
        vb.customize [
          'modifyvm', :id,
          '--name', "Tsg Dev",
          '--memory', 2048,
          "--cpus", 2
        ]
    end

    config.ssh.forward_agent = true

    # Fix stdin: is not a tty error
    config.ssh.shell = "bash -c 'BASH_ENV=/etc/profile exec bash'"

	config.vm.provision "shell", :privileged => false,
		inline: "cd /vagrant && docker-compose up -d"

    if Vagrant.has_plugin?("vagrant-timezone")
        config.timezone.value = "America/New_York"
    end
end
